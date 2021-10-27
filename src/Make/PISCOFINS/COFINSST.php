<?php

/**
 * Trait Helper para tags relacionados ao COFINS de Substituição Triburária
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

namespace NFePHP\NFe\Make\PISCOFINS;

trait COFINSST
{
    /**
     * @var array<\DOMElement>
     */
    protected $aCOFINSST = [];

    /**
     * Grupo COFINS Substituição Tributária T01 pai M01
     * tag NFe/infNFe/det[]/imposto/COFINSST (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagCOFINSST(\stdClass $std)
    {
        $possible = [
            'item',
            'vCOFINS',
            'vBC',
            'pCOFINS',
            'qBCProd',
            'vAliqProd',
            'indSomaCOFINSST'
        ];

        $std = $this->equilizeParameters($std, $possible);

        if ($std->indSomaCOFINSST == 1) {
            $this->stdTot->vCOFINSST += $std->vCOFINS;
        }

        $identificador = "T01 <COFINSST> - [item $std->item]:";

        $cofinsst = $this->buildCOFINSST($std, $identificador);

        $this->aCOFINSST[$std->item] = $cofinsst;
        return $cofinsst;
    }


    /**
     * Função que cria a tag COFINSST
     * tag NFe/infNFe/det[]/imposto/COFINSST
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildCOFINSST(\stdClass $std, $identificador)
    {
        $cofinsst = $this->dom->createElement('COFINSST');

        if (!isset($std->qBCProd)) {
            $this->dom->addChild(
                $cofinsst,
                'vBC',
                $this->conditionalNumberFormatting($std->vBC),
                true,
                "{$identificador} Valor da Base de Cálculo da COFINS"
            );
            $this->dom->addChild(
                $cofinsst,
                'pCOFINS',
                $this->conditionalNumberFormatting($std->pCOFINS, 4),
                true,
                "{$identificador} Alíquota da COFINS (em percentual)"
            );
        } else {
            $this->dom->addChild(
                $cofinsst,
                'qBCProd',
                $this->conditionalNumberFormatting($std->qBCProd, 4),
                true,
                "{$identificador} Quantidade Vendida"
            );
            $this->dom->addChild(
                $cofinsst,
                'vAliqProd',
                $this->conditionalNumberFormatting($std->vAliqProd, 4),
                true,
                "{$identificador} Alíquota da COFINS (em reais)"
            );
        }
        $this->dom->addChild(
            $cofinsst,
            'vCOFINS',
            $this->conditionalNumberFormatting($std->vCOFINS),
            true,
            "{$identificador} Valor da COFINS"
        );
        $this->dom->addChild(
            $cofinsst,
            'indSomaCOFINSST',
            isset($std->indSomaCOFINSST) ? $std->indSomaCOFINSST : null,
            false,
            "{$identificador} Valor da COFINS"
        );

        return $cofinsst;
    }
}
