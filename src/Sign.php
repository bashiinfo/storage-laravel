<?php
namespace Bashi;

class Sign
{

    private static $expireSeconds = 300;

    public static function genereate($appKey, $options)
    {
        $options['time'] = $options['time'] + self::$expireSeconds;
        ksort($options);
        $options = array_map(function ($v) use ($options) {
            return $v . '=' . $options[$v];
        }, array_keys($options));
        return md5(md5($appKey) . sha1(implode('&', $options)));
    }

    /**
     *
     * @param string $sign
     * @param array $options
     * @return number -1 时间过期 0签名不正确 1签名正确
     */
    public static function check($sign, $appKey, $options)
    {
        // 判断是否过期
        if ($options['time'] + self::$expireSeconds > time()) {
            return - 1;
        }
        return $sign = self::genereate($appKey, $options)?1:0;
    }
}