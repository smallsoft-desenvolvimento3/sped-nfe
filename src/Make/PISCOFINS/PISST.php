<?php

/**
 * Trait Helper para impostos relacionados a PIS
 * Esta trait basica está estruturada para montar as tags de PIS para o
 * layout versão 4.00, os demais modelos serão derivados deste
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

trait PISST
{
    /**
     * @var array<\DOMElement>
     */
    protected $aPISST = [];

    /**
     * Grupo PIS Substituição Tributária R01 pai M01
     * tag NFe/infNFe/det[]/imposto/PISST (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagPISST(\stdClass $std)
    {
        $possible = [
            'item',
            'vPIS',
            'vBC',
            'pPIS',
            'qBCProd',
            'vAliqProd',
            'indSomaPISST',
        ];

        $std = $this->equilizeParameters($std, $possible);

        if ($std->indSomaPISST == 1) {
            $this->stdTot->vPISST += $std->vPIS;
        }

        $identificador = 'R01 <PISST> -';

        $pisst = $this->buildPISST($std, $identificador);

        $this->aPISST[$std->item] = $pisst;
        return $pisst;
    }

    /**
     * Função que cria a tag PISST
     * tag NFe/infNFe/det[]/imposto/PISST
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildPISST(\stdClass $std, $identificador)
    {
        $pisst = $this->dom->createElement('PISST');

        if (!isset($std->qBCProd)) {
            $this->dom->addChild(
                $pisst,
                'vBC',
                $this->conditionalNumberFormatting($std->vBC),
                true,
                "{$identificador} [item $std->item] Valor da Base de Cálculo do PIS"
            );
            $this->dom->addChild(
                $pisst,
                'pPIS',
                $this->conditionalNumberFormatting($std->pPIS, 4),
                true,
                "{$identificador} [item $std->item] Alíquota do PIS (em percentual)"
            );
        } else {
            $this->dom->addChild(
                $pisst,
                'qBCProd',
                $this->conditionalNumberFormatting($std->qBCProd, 4),
                true,
                "{$identificador} [item $std->item] Quantidade Vendida"
            );
            $this->dom->addChild(
                $pisst,
                'vAliqProd',
                $this->conditionalNumberFormatting($std->vAliqProd, 4),
                true,
                "{$identificador} [item $std->item] Alíquota do PIS (em reais)"
            );
        }
        $this->dom->addChild(
            $pisst,
            'vPIS',
            $this->conditionalNumberFormatting($std->vPIS),
            true,
            "{$identificador} [item $std->item] Valor do PIS"
        );
        $this->dom->addChild(
            $pisst,
            'indSomaPISST',
            isset($std->indSomaPISST) ? $std->indSomaPISST : null,
            false,
            "{$identificador} [item $std->item] Indica se o valor do PISST compõe o valor total da NF-e"
        );
        return $pisst;
    }
}
