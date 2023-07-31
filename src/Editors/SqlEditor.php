<?php

namespace Aman5537jains\ReportBuilder\Editors;

use PHPHtmlParser\Dom;

class SqlEditor extends Editor
{
    public $bindings = [];

    public function createQuery($html)
    {
        $html = str_replace('[[', '<conditional>', $html);
        $html = str_replace(']]', '</conditional>', $html);
        $html = str_replace('{{', '<variable>', $html);
        $html = str_replace('}}', '</variable>', $html);
        $html = "<sql>$html</sql>";
        $dom = new Dom();
        $dom->loadStr($html);
        // dd((new Options())->setWhitespaceTextNode(false) );
        $dom->setOptions(
            // this is set as the global option level.
            ['whitespaceTextNode' => false]
        );

        $sql = $dom->find('sql');
        $q = '';
        $this->getchilds($sql, $q);

        return $q;
    }

    public function requestC($var)
    {
        $var = trim($var);
        if (isset($this->report->variables[$var])) {
            $val = $this->report->variables[$var]['obj']->queryValue();
            // dump($vars[$var]);
            return $val;
        }

        return false;
    }

    public function setBindings($paramsValue)
    {
        if (is_array($paramsValue)) {
            $paramsValue['sql'];
            $paramsValue['params'];
            foreach ($paramsValue['params'] as $val) {
                $this->bindings[] = $val;
            }

            return $paramsValue['sql'];
        }

        $this->bindings[] = $paramsValue;

        return '?';
    }

    public function getchilds($sql, &$query = '')
    {
        // echo ""
        // $query="";
        $include = true;
        $queryInner = '';
        foreach ($sql as $s) {
            // dump($s);
            // echo "&nbsp;&nbsp;&nbsp;";
            if ($s->isTextNode()) {
                $queryInner .= $s->text();
                // getchilds( $s->getChildren());
            } elseif (!$s->isTextNode() && $s->hasChildren()) {
                // dump($s->getTag()->name()  );
                if ($s->getTag()->name() == 'sql') {
                    $this->getchilds($s->getChildren(), $query);
                } elseif ($s->getTag()->name() == 'conditional') {
                    $new = '';
                    $this->getchilds($s->getChildren(), $new);
                    $queryInner .= $new;
                    // getchilds( );
                } elseif ($s->getTag()->name() == 'variable') {
                    //  echo ( );
                    $output = $this->requestC($s->getChildren()[0]->text());
                    if (!$output) {
                        $include = false;
                    } else {
                        $binding = $this->setBindings($output);

                        $queryInner .= $binding;
                    }
                }
            }
        }
        if ($include) {
            $query .= $queryInner;
        }
    }

    public function build()
    {
        // $this->sql = htmlspecialchars_decode($this->createQuery(htmlspecialchars($this->report->query)));
    }
}
