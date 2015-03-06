<?php

namespace Ojs\Common\Params;

class EventLogParams
{

    static function adminLevelEventLogs(){

        return array(
            ArticleEventLogParams::$ARTICLE_REMOVE,
            ArticleEventLogParams::$ARTICLE_SUBMISSION,
            ArticleEventLogParams::$ARTICLE_APPROVE,
            ArticleEventLogParams::$ARTICLE_UPDATE,
            ProxyEventLogParams::$PROXY_CREATE,
            ProxyEventLogParams::$PROXY_DROP,
            UserEventLogParams::$USER_ADD,
            UserEventLogParams::$USER_LOGIN,
            UserEventLogParams::$USER_LOGOUT,
            UserEventLogParams::$PASSWORD_CHANGE,
        );
    }

    static function editorLevelEventLogs(){

        return array(
            ArticleEventLogParams::$ARTICLE_REMOVE,
            ArticleEventLogParams::$ARTICLE_SUBMISSION,
            ArticleEventLogParams::$ARTICLE_APPROVE,
            ArticleEventLogParams::$ARTICLE_UPDATE,
            ProxyEventLogParams::$PROXY_CREATE,
            ProxyEventLogParams::$PROXY_DROP,
            UserEventLogParams::$USER_LOGIN,
            UserEventLogParams::$USER_LOGOUT,
            UserEventLogParams::$PASSWORD_CHANGE,
        );
    }

    static function authorLevelEventLogs(){

        return array(
            ArticleEventLogParams::$ARTICLE_REMOVE,
            ArticleEventLogParams::$ARTICLE_SUBMISSION,
            ArticleEventLogParams::$ARTICLE_APPROVE,
            ArticleEventLogParams::$ARTICLE_UPDATE,
            ProxyEventLogParams::$PROXY_CREATE,
            ProxyEventLogParams::$PROXY_DROP,
            UserEventLogParams::$USER_LOGIN,
            UserEventLogParams::$USER_LOGOUT,
            UserEventLogParams::$PASSWORD_CHANGE,
        );
    }

    static function userLevelEventLogs(){

        return array(
            ProxyEventLogParams::$PROXY_CREATE,
            ProxyEventLogParams::$PROXY_DROP,
            UserEventLogParams::$USER_LOGIN,
            UserEventLogParams::$USER_LOGOUT,
            UserEventLogParams::$PASSWORD_CHANGE,
        );
    }
}
