<?php

/**
 * Trait Helper para tags relacionados ao número RECOPI
 *
 * @category  API
 * @package   NFePHP\NFe\
 * @copyright Copyright (c) 2008-2021
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @author    Gustavo Lidani <lidanig0 at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfe for the canonical source repository
 */

namespace NFePHP\NFe\Make\Det;

trait RECOPI
{
    /**
     * @var array<\DOMElement>
     */
    protected $aRECOPI = [];

    /**
     * tag NFe/infNFe/det[item]/prod/nRECOPI
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagRECOPI(\stdClass $std)
    {
        $possible = ['item', 'nRECOPI'];
        $std = $this->equilizeParameters($std, $possible);
        $recopi = $this->dom->createElement("nRECOPI", $std->nRECOPI);
        $this->aRECOPI[$std->item] = $recopi;
        return $recopi;
    }
}
