<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Translation\Catalogue\MergeOperation;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Translator;

/**
 * Helps finding unused or missing translation messages in a given locale
 * and comparing them with the fallback ones.
 *
 * @author Florian Voutzinos <florian@voutzinos.com>
 */
class TranslateManageCommand extends ContainerAwareCommand {

    const MESSAGE_MISSING = 0;
    const MESSAGE_UNUSED = 1;
    const MESSAGE_EQUALS_FALLBACK = 2;

    /**
     * {@inheritdoc}
     */
    protected function configure() {
        $this
                ->setName('ojs:translate')
                ->setDefinition(array(
                    new InputArgument('locale', InputArgument::REQUIRED, 'The locale'),
                    new InputArgument('bundle', InputArgument::REQUIRED, 'The bundle names with commas'),
                    new InputOption('domain', null, InputOption::VALUE_OPTIONAL, 'The messages domain'),
                    new InputOption('only-missing', null, InputOption::VALUE_NONE, 'Displays only missing messages'),
                    new InputOption('only-unused', null, InputOption::VALUE_NONE, 'Displays only unused messages'),
                ))
                ->setDescription('Displays translation messages informations')
                ->setHelp(<<<EOF
The <info>%command.name%</info> command helps finding unused or missing translation
messages and comparing them with the fallback ones by inspecting the
templates and translation files of a given bundle.

You can display information about bundle translations in a specific locale:

<info>php %command.full_name% en AcmeDemoBundle</info>

You can also specify a translation domain for the search:

<info>php %command.full_name% --domain=messages en AcmeDemoBundle</info>

You can only display missing messages:

<info>php %command.full_name% --only-missing en AcmeDemoBundle</info>

You can only display unused messages:

<info>php %command.full_name% --only-unused en AcmeDemoBundle</info>

EOF
                )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $locale = $input->getArgument('locale');
        $domain = $input->getOption('domain');
        $bundle_array = explode(",", $input->getArgument('bundle'));

        foreach ($bundle_array as $bundle_name) {

            $bundle_name = "Ojs" . $bundle_name . "Bundle";

            $bundle = $this->getContainer()->get('kernel')->getBundle($bundle_name);
            $loader = $this->getContainer()->get('translation.loader');

            // Extract used messages
            $extractedCatalogue[$bundle_name] = new MessageCatalogue($locale);
            $this->getContainer()->get('translation.extractor')->extract($bundle->getPath() . '/Resources/views', $extractedCatalogue[$bundle_name]);

            // Load defined messages
            $currentCatalogue[$bundle_name] = new MessageCatalogue($locale);
            if (is_dir($bundle->getPath() . '/Resources/translations')) {
                $loader->loadMessages($bundle->getPath() . '/Resources/translations', $currentCatalogue[$bundle_name]);
            }

            // Merge defined and extracted messages to get all message ids
            foreach ($currentCatalogue as $currentCatalogue_item) {
                $mergeOperation = new MergeOperation($extractedCatalogue[$bundle_name], $currentCatalogue_item);
                $allMessages[$bundle_name] = $mergeOperation->getResult()->all($domain);
                if (null !== $domain) {
                    $allMessages[$bundle_name] = array($domain => $allMessages[$bundle_name]);
                }
            }


            // No defined or extracted messages
            if (empty($allMessages) || null !== $domain && empty($allMessages[$bundle_name][$domain])) {
                $outputMessage = sprintf('<info>No defined or extracted messages for locale "%s"</info>', $locale);

                if (null !== $domain) {
                    $outputMessage .= sprintf(' <info>and domain "%s"</info>', $domain);
                }

                $output->writeln($outputMessage);

                return;
            }

            // Load the fallback catalogues
            $fallbackCatalogues = array();
            $translator = $this->getContainer()->get('translator');
            if ($translator instanceof Translator) {
                foreach ($translator->getFallbackLocales() as $fallbackLocale) {
                    if ($fallbackLocale === $locale) {
                        continue;
                    }

                    $fallbackCatalogue = new MessageCatalogue($fallbackLocale);
                    $loader->loadMessages($bundle->getPath() . '/Resources/translations', $fallbackCatalogue);
                    $fallbackCatalogues[] = $fallbackCatalogue;
                }
            }
        }
        /** @var \Symfony\Component\Console\Helper\Table $table */
        $table = new Table($output);

        // Display header line
        $headers = array('Bundle', 'State(s)', 'Id', sprintf('Message Preview (%s)', $locale));
        foreach ($fallbackCatalogues as $fallbackCatalogue) {
            $headers[] = sprintf('Fallback Message Preview (%s)', $fallbackCatalogue->getLocale());
        }
        $table->setHeaders($headers);

        // Iterate all message ids and determine their state
        foreach ($allMessages as $bundle => $bundle_a) {
            foreach ($bundle_a as $domain => $messages) {
                foreach (array_keys($messages) as $messageId) {
                $value = $currentCatalogue[$bundle]->get($messageId, $domain);
                    $states = array();

                    if ($extractedCatalogue[$bundle]->defines($messageId, $domain)) {
                        if (!$currentCatalogue[$bundle]->defines($messageId, $domain)) {
                            $states[] = self::MESSAGE_MISSING;
                        }
                    } elseif ($currentCatalogue[$bundle]->defines($messageId, $domain)) {
                        $states[] = self::MESSAGE_UNUSED;
                    }

                    if (!in_array(self::MESSAGE_UNUSED, $states) && true === $input->getOption('only-unused') || !in_array(self::MESSAGE_MISSING, $states) && true === $input->getOption('only-missing')) {
                        continue;
                    }

                    foreach ($fallbackCatalogues as $fallbackCatalogue) {
                        if ($fallbackCatalogue->defines($messageId, $domain) && $value === $fallbackCatalogue->get($messageId, $domain)) {
                            $states[] = self::MESSAGE_EQUALS_FALLBACK;

                            break;
                        }
                    }

                    $row = array($bundle, $this->formatStates($states), $this->formatId($messageId), $this->sanitizeString($value));
                    foreach ($fallbackCatalogues as $fallbackCatalogue) {
                        $row[] = $this->sanitizeString($fallbackCatalogue->get($messageId, $domain));
                    }

                    $table->addRow($row);
                }
            }
        }

        $table->render();

        $output->writeln('');
        $output->writeln('<info>Legend:</info>');
        $output->writeln(sprintf(' %s Missing message', $this->formatState(self::MESSAGE_MISSING)));
        $output->writeln(sprintf(' %s Unused message', $this->formatState(self::MESSAGE_UNUSED)));
        $output->writeln(sprintf(' %s Same as the fallback message', $this->formatState(self::MESSAGE_EQUALS_FALLBACK)));
    }

    private function formatState($state) {
        if (self::MESSAGE_MISSING === $state) {
            return '<fg=red>x</>';
        }

        if (self::MESSAGE_UNUSED === $state) {
            return '<fg=yellow>o</>';
        }

        if (self::MESSAGE_EQUALS_FALLBACK === $state) {
            return '<fg=green>=</>';
        }

        return $state;
    }

    private function formatStates(array $states) {
        $result = array();
        foreach ($states as $state) {
            $result[] = $this->formatState($state);
        }

        return implode(' ', $result);
    }

    private function formatId($id) {
        return sprintf('<fg=cyan;options=bold>%s</fg=cyan;options=bold>', $id);
    }

    private function sanitizeString($string, $length = 40) {
        $string = trim(preg_replace('/\s+/', ' ', $string));

        if (function_exists('mb_strlen') && false !== $encoding = mb_detect_encoding($string)) {
            if (mb_strlen($string, $encoding) > $length) {
                return mb_substr($string, 0, $length - 3, $encoding) . '...';
            }
        } elseif (strlen($string) > $length) {
            return substr($string, 0, $length - 3) . '...';
        }

        return $string;
    }

}
