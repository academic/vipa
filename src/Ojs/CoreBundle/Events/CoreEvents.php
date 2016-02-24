<?php

namespace Ojs\CoreBundle\Events;

final class CoreEvents implements MailEventsInterface
{
    const OJS_INSTALL_BASE = 'ojs.core.install.base';
    const OJS_INSTALL_3PARTY = 'ojs.core.install.3party';
    const OJS_PERMISSION_CHECK = 'ojs.core.permission.check';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::OJS_INSTALL_BASE, 'admin', [
                'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
