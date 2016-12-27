<?php

namespace espend\Inspector\ImportBundle\Utils;

use espend\Inspector\ImportBundle\Model\Author;

class DocCommentUtil
{
    /**
     * @param string $comment
     * @return Author[]
     */
    public static function extractAuthor(string $comment) : array
    {
        if (!preg_match_all("#\@author\s*([^<].*)\s*<\s*([^<(> ]*\p{L}+@*\p{L}+)\s*>#i", $comment, $result, PREG_SET_ORDER)) {
            return [];
        }

        $authors = [];

        foreach ($result as $res) {
            $name = trim(preg_replace('#\s+#', ' ', $res[1]));
            $mail = trim(preg_replace('#\s+#', ' ', $res[2]));

            if (strlen($name) > 0 && strlen($name) < 255 && strlen($mail) > 0 && strlen($mail) < 255) {
                $authors[] = $author = new Author();
                $author->name = $name;
                $author->mail = strtolower($mail);
            }
        }

        return $authors;
    }
}