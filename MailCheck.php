<?php

class MailCheck
{
    protected $domains;
    protected $topDomains;
    protected $distanceFunction;
    protected $minDistance;

    public function __construct(array $domains = null, array $topDomains = null, $distanceFunction = null, $minDistance = null)
    {
        $this->domains = $domains ?: array(
            "yahoo.com", "google.com", "hotmail.com", "gmail.com", "me.com", "aol.com", "mac.com",
            "live.com", "comcast.net", "googlemail.com", "msn.com", "facebook.com", "gmx.com", "mail.com");
        $this->topDomains = $topDomains ?: array("fr", "com", "net", "org", "info", "edu", "gov", "mil", "eu");
        $this->distanceFunction = $distanceFunction ?: 'levenshtein';
        $this->minDistance = $minDistance ?: 3;
    }

    public function suggest($email)
    {
        $email = strtolower($email);

        list($user, $domain) = explode('@', $email, 2);

        if (!$domain) {
            return false;
        }

        $closestDomain = $this->findClosest($domain, $this->domains, $this->minDistance);

        if (is_string($closestDomain)) {
            return preg_replace('{'.$domain.'$}', $closestDomain, $email);
        }

        $topDomain = substr($domain, strrpos($domain, '.')+1);
        $closestTopDomain = $this->findClosest($topDomain, $this->topDomains, 1);

        if (is_string($closestTopDomain)) {
            return preg_replace('{'.$topDomain.'$}', $closestTopDomain, $email);
        }

        return false;
    }

    protected function findClosest($string, $collection, $minDistance)
    {
        if (in_array($string, $collection)) {
            return $string;
        }
        $closest = false;

        foreach ($collection as $tested) {
            $distance = call_user_func($this->distanceFunction, $string, $tested);

            if ($distance <= $minDistance) {
                $minDistance = $distance-1;
                $closest = $tested;
            }
        }

        return $closest;
    }
}