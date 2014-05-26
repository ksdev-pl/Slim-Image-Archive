<?php

class CustomUploadHandler extends UploadHandler
{
    /** @type string $uploadedFileName */
    private $uploadedFileName;

    /**
     * @param string $uploadedFileName
     */
    public function setUploadedFileName($uploadedFileName)
    {
        $this->uploadedFileName = $uploadedFileName;
    }

    /**
     * @return string
     */
    public function getUploadedFileName()
    {
        return $this->uploadedFileName;
    }

    /**
     * Transform trimmed filename to filesystem-friendly string
     *
     * @param $file_path
     * @param $name
     * @param $size
     * @param $type
     * @param $error
     * @param $index
     * @param $content_range
     *
     * @return string
     */
    protected function trim_file_name($file_path, $name, $size, $type, $error, $index, $content_range) {
        $trimmedName = parent::trim_file_name($file_path, $name, $size, $type, $error, $index, $content_range);
        $fileNameParts = pathinfo($trimmedName);
        $slugOfName = $this->slug($fileNameParts['filename']);
        $resultFileName = $slugOfName . '.' . $fileNameParts['extension'];
        $this->setUploadedFileName($resultFileName);

        return $resultFileName;
    }

    /**
     * Return a URL/filesystem-friendly version of string
     *
     * @author Bong Cosca
     * @copyright 2009-2014 Bong Cosca
     * @link http://fatfreeframework.com
     * @license http://www.gnu.org/licenses/gpl-3.0.html
     *
     * @param string $text
     *
     * @return string
     */
    protected function slug($text) {
        return date("YmdHis").'-'.trim(strtolower(preg_replace('/([^\pL\pN])+/u','-',
            trim(strtr(str_replace('\'','',$text),
                array(
                    'Ǎ'=>'A','А'=>'A','Ā'=>'A','Ă'=>'A','Ą'=>'A','Å'=>'A',
                    'Ǻ'=>'A','Ä'=>'Ae','Á'=>'A','À'=>'A','Ã'=>'A','Â'=>'A',
                    'Æ'=>'AE','Ǽ'=>'AE','Б'=>'B','Ç'=>'C','Ć'=>'C','Ĉ'=>'C',
                    'Č'=>'C','Ċ'=>'C','Ц'=>'C','Ч'=>'Ch','Ð'=>'Dj','Đ'=>'Dj',
                    'Ď'=>'Dj','Д'=>'Dj','É'=>'E','Ę'=>'E','Ё'=>'E','Ė'=>'E',
                    'Ê'=>'E','Ě'=>'E','Ē'=>'E','È'=>'E','Е'=>'E','Э'=>'E',
                    'Ë'=>'E','Ĕ'=>'E','Ф'=>'F','Г'=>'G','Ģ'=>'G','Ġ'=>'G',
                    'Ĝ'=>'G','Ğ'=>'G','Х'=>'H','Ĥ'=>'H','Ħ'=>'H','Ï'=>'I',
                    'Ĭ'=>'I','İ'=>'I','Į'=>'I','Ī'=>'I','Í'=>'I','Ì'=>'I',
                    'И'=>'I','Ǐ'=>'I','Ĩ'=>'I','Î'=>'I','Ĳ'=>'IJ','Ĵ'=>'J',
                    'Й'=>'J','Я'=>'Ja','Ю'=>'Ju','К'=>'K','Ķ'=>'K','Ĺ'=>'L',
                    'Л'=>'L','Ł'=>'L','Ŀ'=>'L','Ļ'=>'L','Ľ'=>'L','М'=>'M',
                    'Н'=>'N','Ń'=>'N','Ñ'=>'N','Ņ'=>'N','Ň'=>'N','Ō'=>'O',
                    'О'=>'O','Ǿ'=>'O','Ǒ'=>'O','Ơ'=>'O','Ŏ'=>'O','Ő'=>'O',
                    'Ø'=>'O','Ö'=>'Oe','Õ'=>'O','Ó'=>'O','Ò'=>'O','Ô'=>'O',
                    'Œ'=>'OE','П'=>'P','Ŗ'=>'R','Р'=>'R','Ř'=>'R','Ŕ'=>'R',
                    'Ŝ'=>'S','Ş'=>'S','Š'=>'S','Ș'=>'S','Ś'=>'S','С'=>'S',
                    'Ш'=>'Sh','Щ'=>'Shch','Ť'=>'T','Ŧ'=>'T','Ţ'=>'T','Ț'=>'T',
                    'Т'=>'T','Ů'=>'U','Ű'=>'U','Ŭ'=>'U','Ũ'=>'U','Ų'=>'U',
                    'Ū'=>'U','Ǜ'=>'U','Ǚ'=>'U','Ù'=>'U','Ú'=>'U','Ü'=>'Ue',
                    'Ǘ'=>'U','Ǖ'=>'U','У'=>'U','Ư'=>'U','Ǔ'=>'U','Û'=>'U',
                    'В'=>'V','Ŵ'=>'W','Ы'=>'Y','Ŷ'=>'Y','Ý'=>'Y','Ÿ'=>'Y',
                    'Ź'=>'Z','З'=>'Z','Ż'=>'Z','Ž'=>'Z','Ж'=>'Zh','á'=>'a',
                    'ă'=>'a','â'=>'a','à'=>'a','ā'=>'a','ǻ'=>'a','å'=>'a',
                    'ä'=>'ae','ą'=>'a','ǎ'=>'a','ã'=>'a','а'=>'a','ª'=>'a',
                    'æ'=>'ae','ǽ'=>'ae','б'=>'b','č'=>'c','ç'=>'c','ц'=>'c',
                    'ċ'=>'c','ĉ'=>'c','ć'=>'c','ч'=>'ch','ð'=>'dj','ď'=>'dj',
                    'д'=>'dj','đ'=>'dj','э'=>'e','é'=>'e','ё'=>'e','ë'=>'e',
                    'ê'=>'e','е'=>'e','ĕ'=>'e','è'=>'e','ę'=>'e','ě'=>'e',
                    'ė'=>'e','ē'=>'e','ƒ'=>'f','ф'=>'f','ġ'=>'g','ĝ'=>'g',
                    'ğ'=>'g','г'=>'g','ģ'=>'g','х'=>'h','ĥ'=>'h','ħ'=>'h',
                    'ǐ'=>'i','ĭ'=>'i','и'=>'i','ī'=>'i','ĩ'=>'i','į'=>'i',
                    'ı'=>'i','ì'=>'i','î'=>'i','í'=>'i','ï'=>'i','ĳ'=>'ij',
                    'ĵ'=>'j','й'=>'j','я'=>'ja','ю'=>'ju','ķ'=>'k','к'=>'k',
                    'ľ'=>'l','ł'=>'l','ŀ'=>'l','ĺ'=>'l','ļ'=>'l','л'=>'l',
                    'м'=>'m','ņ'=>'n','ñ'=>'n','ń'=>'n','н'=>'n','ň'=>'n',
                    'ŉ'=>'n','ó'=>'o','ò'=>'o','ǒ'=>'o','ő'=>'o','о'=>'o',
                    'ō'=>'o','º'=>'o','ơ'=>'o','ŏ'=>'o','ô'=>'o','ö'=>'oe',
                    'õ'=>'o','ø'=>'o','ǿ'=>'o','œ'=>'oe','п'=>'p','р'=>'r',
                    'ř'=>'r','ŕ'=>'r','ŗ'=>'r','ſ'=>'s','ŝ'=>'s','ș'=>'s',
                    'š'=>'s','ś'=>'s','с'=>'s','ş'=>'s','ш'=>'sh','щ'=>'shch',
                    'ß'=>'ss','ţ'=>'t','т'=>'t','ŧ'=>'t','ť'=>'t','ț'=>'t',
                    'у'=>'u','ǘ'=>'u','ŭ'=>'u','û'=>'u','ú'=>'u','ų'=>'u',
                    'ù'=>'u','ű'=>'u','ů'=>'u','ư'=>'u','ū'=>'u','ǚ'=>'u',
                    'ǜ'=>'u','ǔ'=>'u','ǖ'=>'u','ũ'=>'u','ü'=>'ue','в'=>'v',
                    'ŵ'=>'w','ы'=>'y','ÿ'=>'y','ý'=>'y','ŷ'=>'y','ź'=>'z',
                    'ž'=>'z','з'=>'z','ż'=>'z','ж'=>'zh'
                ))))),'-');
    }
}