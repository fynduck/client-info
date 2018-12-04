<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/12/18
 * Time: 3:51 PM
 */

namespace Fynduck\ClientInfo;


trait ClientInfo
{
    public function getPlatform($agent = null)
    {
        if (!$agent)
            $agent = request()->header('User-Agent');

        $platform = "Unknown OS Platform";

        $os_array = [
            '/windows|win32/i'                  => 'Windows',
            '/mac_powerpc|macintosh|mac os x/i' => 'Mac',
            '/linux/i'                          => 'Linux',
            '/ubuntu/i'                         => 'Ubuntu',
            '/iphone/i'                         => 'iPhone',
            '/ipod/i'                           => 'iPod',
            '/ipad/i'                           => 'iPad',
            '/android/i'                        => 'Android',
            '/blackberry/i'                     => 'BlackBerry',
            '/webos/i'                          => 'Mobile'
        ];

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $agent))
                $platform = $value;

        return $platform;
    }

    public function getPlatformVersion($agent = null)
    {
        if (!$agent)
            $agent = request()->header('User-Agent');

        $platform_v = 0;

        $os_array = array(
            '/windows nt 10/i'      => '10',
            '/windows nt 6.3/i'     => '8.1',
            '/windows nt 6.2/i'     => '8',
            '/windows nt 6.1/i'     => '7',
            '/windows nt 6.0/i'     => 'Vista',
            '/windows nt 5.2/i'     => 'Server 2003/XP x64',
            '/windows nt 5.1/i'     => 'XP',
            '/windows xp/i'         => 'XP',
            '/windows nt 5.0/i'     => '2000',
            '/windows me/i'         => 'ME',
            '/win98/i'              => '98',
            '/win95/i'              => '95',
            '/win16/i'              => '3.11',
            '/macintosh|mac os x/i' => 'X',
            '/mac_powerpc/i'        => '9'
        );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $agent))
                $platform_v = $value;

        return $platform_v;
    }

    public function getBrowserName($agent = null)
    {
        if (!$agent)
            $agent = request()->header('User-Agent');

        $data = ['browserName' => 'Unknown Browser', 'ub' => ''];

        $browser_array = [
            '/msie/i'      => 'Internet Explorer',
            '/firefox/i'   => 'Firefox',
            '/safari/i'    => 'Safari',
            '/chrome/i'    => 'Chrome',
            '/edge/i'      => 'Edge',
            '/opera/i'     => 'Opera',
            '/netscape/i'  => 'Netscape',
            '/maxthon/i'   => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i'    => 'Handheld Browser',
            '/YaBrowser/i' => 'Yandex browser',
        ];

        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex, $agent)) {
                $data['browserName'] = $value;
                $data['ub'] = ucfirst(str_replace(['/i', '/'], '', $regex));
            } elseif (strpos($agent, 'bot') !== false) {
                $data['browserName'] = 'Bots';
                $data['ub'] = 'Bot';
            }
        };

        return $data;
    }

    public function getBrowserVersion($agent = null, $ub = null)
    {
        if (!$agent)
            $agent = request()->header('User-Agent');

        if (!$ub) {
            $browserInfo = $this->getBrowserName($agent);
            if ($browserInfo && isset($browserInfo['ub']) && $browserInfo['ub'])
                return $this->getBrowserVersion($agent, $browserInfo['ub']);

            return null;
        }

        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        preg_match_all($pattern, $agent, $matches);

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($agent, "Version") < strripos($agent, $ub)) {
                $data['version'] = isset($matches['version'][0]) ? $matches['version'][0] : 0;
            } else {
                if (!empty($matches['version']) && isset($matches['version'][1])) {
                    $data['version'] = $matches['version'][1];
                } else {
                    $data['version'] = 0;
                }
            }
        } else {
            $data['version'] = isset($matches['version'][0]) ? $matches['version'][0] : 0;
        }

        $data['pattern '] = $pattern;

        return $data;
    }

    public function getDomainReferer($referrer = null)
    {
        $domain = null;

        if (!$referrer && request()->header('referer'))
            $referrer = request()->header('referer');

        if ($referrer) {
            $url = parse_url($referrer);

            if (!isset($url['host']))
                return null;

            $parts = explode('.', $url['host']);

            $domain = array_pop($parts);
            if (count($parts) > 0)
                $domain = array_pop($parts) . '.' . $domain;
        }

        return $domain;
    }
}
