<?php

/**
 * Trait Helper para tags relacionados a Impostos com o valor total tributado
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

trait Imposto
{
    /**
     * @var array<\DOMElement>
     */
    protected $aImposto = [];

    /**
     * Impostos com o valor total tributado M01 pai H01
     * tag NFe/infNFe/det[]/imposto
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagimposto(\stdClass $std)
    {
        $possible = ['item', 'vTotTrib'];
        $std = $this->equilizeParameters($std, $possible);

        // totalizador dos valores dos itens
        $this->stdTot->vTotTrib += (float) $std->vTotTrib;

        $identificador = "M01 <imposto> - [item {$std->item}]:";

        $imposto = $this->dom->createElement("imposto");

        $this->dom->addChild(
            $imposto,
            "vTotTrib",
            $this->conditionalNumberFormatting($std->vTotTrib),
            false,
            "$identificador Valor aproximado total de tributos federais, estaduais e municipais."
        );
        $this->aImposto[$std->item] = $imposto;
        return $imposto;
    }

    /**
     * Insere dentro dentro das tags imposto o ICMS IPI II PIS COFINS ISSQN
     * tag NFe/infNFe/det[]/imposto
     * @return void
     */
    protected function buildImp()
    {
        foreach ($this->aImposto as $nItem => $imposto) {
            if (!empty($this->aICMS[$nItem])) {
                $this->dom->appChild($imposto, $this->aICMS[$nItem], "Inclusão do node ICMS");
            }
            if (!empty($this->aIPI[$nItem])) {
                $this->dom->appChild($imposto, $this->aIPI[$nItem], "Inclusão do node IPI");
            }
            if (!empty($this->aII[$nItem])) {
                $this->dom->appChild($imposto, $this->aII[$nItem], "Inclusão do node II");
            }
            if (!empty($this->aISSQN[$nItem])) {
                $this->dom->appChild($imposto, $this->aISSQN[$nItem], "Inclusão do node ISSQN");
            }
            if (!empty($this->aPIS[$nItem])) {
                $this->dom->appChild($imposto, $this->aPIS[$nItem], "Inclusão do node PIS");
            }
            if (!empty($this->aPISST[$nItem])) {
                $this->dom->appChild($imposto, $this->aPISST[$nItem], "Inclusão do node PISST");
            }
            if (!empty($this->aCOFINS[$nItem])) {
                $this->dom->appChild($imposto, $this->aCOFINS[$nItem], "Inclusão do node COFINS");
            }
            if (!empty($this->aCOFINSST[$nItem])) {
                $this->dom->appChild($imposto, $this->aCOFINSST[$nItem], "Inclusão do node COFINSST");
            }
            if (!empty($this->aICMSUFDest[$nItem])) {
                $this->dom->appChild($imposto, $this->aICMSUFDest[$nItem], "Inclusão do node ICMSUFDest");
            }
            $this->aImposto[$nItem] = $imposto;
        }
    }
}
