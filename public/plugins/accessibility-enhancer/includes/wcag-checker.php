<?php

class WCAG_Checker {
    public static function check_missing_alt_tags($content) {
        preg_match_all('/<img[^>]+>/i', $content, $images);
        $issues = [];

        foreach ($images[0] as $img) {
            if (!preg_match('/alt=["\'].*?["\']/', $img)) {
                $issues[] = $img;
            }
        }

        return $issues;
    }
}
