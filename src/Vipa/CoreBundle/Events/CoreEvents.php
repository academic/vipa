<?php

namespace Vipa\CoreBundle\Events;

final class CoreEvents implements MailEventsInterface
{
    const OJS_INSTALL_BASE = 'vipa.core.install.base';
    const OJS_INSTALL_3PARTY = 'vipa.core.install.3party';
    const OJS_PERMISSION_CHECK = 'vipa.core.permission.check';

    public function getMailEventsOptions()
    {
        return [
            new EventDetail(self::OJS_INSTALL_3PARTY, 'admin', [
                'bundleName', 'receiver.username', 'receiver.fullName',
            ]),
        ];
    }
}
