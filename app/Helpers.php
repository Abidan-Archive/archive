<?php

// Diffs from...
// https://github.com/paulgb/simplediff/blob/5bfe1d2a8f967c7901ace50f04ac2d9308ed3169/simplediff.php
if (! function_exists('diff')) {
    function diff($old, $new)
    {
        $maxlen = 0;
        foreach ($old as $oindex => $ovalue) {
            $nkeys = array_keys($new, $ovalue);
            foreach ($nkeys as $nindex) {
                $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
                    $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
                if ($matrix[$oindex][$nindex] > $maxlen) {
                    $maxlen = $matrix[$oindex][$nindex];
                    $omax = $oindex + 1 - $maxlen;
                    $nmax = $nindex + 1 - $maxlen;
                }
            }
        }
        if ($maxlen == 0) {
            return [['d' => $old, 'i' => $new]];
        }

        return array_merge(
            diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
            array_slice($new, $nmax, $maxlen),
            diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
    }
}

if (! function_exists('htmlDiff')) {
    function htmlDiff($old, $new)
    {
        $diff = diff(explode(' ', $old), explode(' ', $new));
        $ret = '';
        foreach ($diff as $k) {
            if (is_array($k)) {
                $ret .= (! empty($k['d']) ? '<del>'.implode(' ', $k['d']).'</del> ' : '').
                    (! empty($k['i']) ? '<ins>'.implode(' ', $k['i']).'</ins> ' : '');
            } else {
                $ret .= $k.' ';
            }
        }

        return $ret;
    }
}

// https://www.codexworld.com/how-to/get-domain-name-from-url-php/
if (! function_exists('getDomain')) {
    function getDomain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }

        return null;
    }
}
