<?php namespace App\Helpers;

class Helper {

    /**
     * mapKeys(array $orga, array $repla)
     *
     * Copy the value of the keys in array $arga, that also exist in array $repla, to a new array, using the value in $repla as keyname.
     * This was made to convert some key names in Models to the key names expected in (general) templates.
     *
     * @param array $orga
     * @param array $repla
     * @return array
     */
    static function mapKeys(array $orga, array $repla) : array {
        $new=array();
        foreach ($orga as $k => $v) {
            if (array_key_exists($k, $repla)) {
                $new[$repla[$k]] = $v;
            }
        }
        //dd($orga,$new);
        return $new;
    }

    static function brToSpace($s) : String {
        return str_replace(['<br>','<br />'],' ',$s);
    }

    static function brbrToP($s) : String {
        return str_replace(['<br><br>','<br /><br />'],'</p><p>',$s);
    }

    public function testMeNow($s) {
        return $s;
    }

    public static function likeFilter($s) {
        if (strpos($s, '%') === false) {
            if (empty($s)) {
                $s = '%';
            } else {
                $s = '%' . $s . '%';
            }
        }
        return $s;
    }
}
