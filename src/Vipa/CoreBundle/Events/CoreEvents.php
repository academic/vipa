<?php

namespace Vipa\CoreBundle\Events;

final class CoreEvents implements MailEventsInterface
{
    const VIPA_INSTALL_BASE = 'vipa.core.install.base';
    const VIPA_INSTALL_3PARTY = 'vipa.core.install.3party';
    const VIPA_PERMISSION_CHECK = 'vipa.core.permission.check';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::VIPA_INSTALL_3PARTY, 'admin', [
                'bundleName', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
