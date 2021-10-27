<?php

/**
 * Trait Helper para tags relacionados a informações adicionais do item
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

trait InfAdProd
{
    /**
     * @var array<\DOMElement>
     */
    protected $aInfAdProd = [];

    /**
     * Informações adicionais do produto V01 pai H01
     * tag NFe/infNFe/det[]/infAdProd
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function taginfAdProd(\stdClass $std)
    {
        $possible = ['item', 'infAdProd'];
        $std = $this->equilizeParameters($std, $possible);
        $infAdProd = $this->dom->createElement(
            "infAdProd",
            substr(trim($std->infAdProd), 0, 500)
        );
        $this->aInfAdProd[$std->item] = $infAdProd;
        return $infAdProd;
    }
}
