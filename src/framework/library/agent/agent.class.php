<?php

use Detection\MobileDetect;

class Agent
{
    // 设备类型
    const DEVICE_MOBILE = 1;
    const DEVICE_DESKTOP = 2;
    const DEVICE_UNKNOWN = -1;

    // 浏览器类型
    const BROWSER_TYPE_IPHONE = 1;
    const BROWSER_TYPE_IPAD = 2;
    const BROWSER_TYPE_IPOD = 3;
    const BROWSER_TYPE_ANDROID = 4;
    const BROWSER_TYPE_UNKNOWN = -1;

    // 系统类型
    const OS_TYPE_IOS = 1;
    const OS_TYPE_ANDROID = 2;
    const OS_TYPE_UNKNOWN = -1;

    // 是否RETINA屏
    const RETINA_TYPE_YES = 1;
    const RETINA_TYPE_NOT = 0;

    // 是否IOS6系统
    const IOS6_YES = 1;
    const IOS6_NOT = 0;

    // 是否微信打开
    const MICRO_MESSAGE_YES = 1;
    const MICRO_MESSAGE_NOT = 0;

    // APP已经安装
    const APP_INSTALLED_YES = 1;
    const APP_INSTALLED_NOT = 0;

    /**
     * @var MobileDetect
     */
    private $_detect;

    /**
     * @return MobileDetect
     */
    public function getDetect()
    {
        if (self::$_detect === null) {
            self::$_detect = new MobileDetect();
        }

        return self::$_detect;
    }


    /**
     * 获取agent信息
     *
     * @return array
     */
    public static function getDeviceInfo()
    {
        return [
            'deviceType'  => self::deviceType(),
            'browserType' => self::browserType(),
            'isRetina'    => self::isRetina(),
            'osType'      => self::osType(),
            'isIos6'      => self::isIos6(),
        ];
    }

    /**
     * 获取浏览器类型
     *
     * @param string $agent
     *
     * @return int
     */
    public static function browserType($agent = '')
    {
        $detect = static::getDetect();

        if ($agent && $detect->getUserAgent() !== $agent) {
            $detect->setUserAgent($agent);
        }

        if ($detect->isIphone()) {
            return self::BROWSER_TYPE_IPHONE;
        } elseif ($detect->isIpad()) {
            return self::BROWSER_TYPE_IPAD;
        } elseif ($detect->isIpod()) {
            return self::BROWSER_TYPE_IPOD;
        } elseif ($detect->isAndroid()) {
            return self::BROWSER_TYPE_ANDROID;
        } else {
            return self::BROWSER_TYPE_UNKNOWN;
        }
    }

    /**
     * 是否安卓或IOS
     *
     * @param string $agent
     *
     * @return int
     */
    public static function osType($agent = '')
    {
        $detect = static::getDetect();

        if ($agent && $detect->getUserAgent() !== $agent) {
            $detect->setUserAgent($agent);
        }

        if ($detect->isIOS()) {
            return self::OS_TYPE_IOS;
        } elseif ($detect->isAndroid()) {
            return self::OS_TYPE_ANDROID;
        } else {
            return self::OS_TYPE_UNKNOWN;
        }
    }

    /**
     * 是否移动或者PC设备
     *
     * @return int
     */
    public static function deviceType()
    {
        return static::isMobile() ? self::DEVICE_MOBILE : self::DEVICE_DESKTOP;
    }

    /**
     * 是否retina屏
     *
     * @param string $agent
     *
     * @return bool
     */
    public static function isRetina($agent = '')
    {
        return self::osType($agent) === self::OS_TYPE_IOS && self::isIos6($agent) !== self::IOS6_YES ? self::RETINA_TYPE_YES : self::RETINA_TYPE_NOT;
    }

    /**
     *
     * @param string $agent
     *
     * @return int
     */
    public static function isIos6($agent = '')
    {
        $detect = static::getDetect();

        return $detect->is('iOS', $agent) && $detect->version('6') ? self::IOS6_YES : self::IOS6_NOT;
    }

    /**
     * 是否微信中打开
     *
     * @param $agent
     *
     * @return int
     */
    public static function isMicroMessage($agent)
    {
        return static::getDetect()->is('MicroMessenger', $agent) ? self::MICRO_MESSAGE_YES : self::MICRO_MESSAGE_NOT;
    }

    /**
     * 是否移动设备
     *
     * @return bool
     */
    public static function isMobile()
    {
        return static::getDetect()->isMobile();
    }

    /**
     * 是否已安装APP
     *
     * @return int
     */
    public static function isAppInstalled()
    {
        return isset($_GET['isappinstalled']) && ($_GET['isappinstalled'] == 1) ? self::APP_INSTALLED_YES : self::APP_INSTALLED_NOT;
    }

    /**
     * @param string $agent
     *
     * @return string
     */
    public static function getAgent($agent = '')
    {
        $agent = empty($agent) ? $_SERVER['HTTP_USER_AGENT'] : $agent;

        return $agent;
    }
}