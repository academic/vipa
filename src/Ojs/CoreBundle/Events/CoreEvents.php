<?php

namespace Ojs\CoreBundle\Event;


final class AdminEvents
{
    const OJS_INSTALL_BASE = 'ojs.core.install.base';
    const OJS_INSTALL_3PARTY = 'ojs.core.install.3party';
    const OJS_UPLOAD_FILE = 'ojs.core.upload.file';
    const OJS_CROP_FILE = 'ojs.core.crop.file';
    const OJS_ELASTICA_REQUEST = 'ojs.core.elastica.request';
}