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

trait PIS
{

    /**
     * @var array<\DOMElement>
     */
    protected $aPIS = [];

    /**
     * Grupo PIS Q01 pai M01
     * tag NFe/infNFe/det[]/imposto/PIS
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagPIS(\stdClass $std)
    {
        $possible = [
            'item',
            'CST',
            'vBC',
            'pPIS',
            'vPIS',
            'qBCProd',
            'vAliqProd'
        ];

        $std = $this->equilizeParameters($std, $possible);

        /**
         * Identificador da Tag para mostrar nos erros
         */
        $identificador = 'Q01 <PIS> -';

        switch ($std->CST) {
            case '01':
            case '02':
                $pisItem = $this->buildPISAliq($std, $identificador);
                break;
            case '03':
                $pisItem = $this->buildPISQtde($std, $identificador);
                break;
            case '04':
            case '05':
            case '06':
            case '07':
            case '08':
            case '09':
                $pisItem = $this->buildPISNT($std, $identificador);
                break;
            case '49':
            case '50':
            case '51':
            case '52':
            case '53':
            case '54':
            case '55':
            case '56':
            case '60':
            case '61':
            case '62':
            case '63':
            case '64':
            case '65':
            case '66':
            case '67':
            case '70':
            case '71':
            case '72':
            case '73':
            case '74':
            case '75':
            case '98':
            case '99':
                $pisItem = $this->buildPISOutr($std, $identificador);
                break;
        }

        $pis = $this->dom->createElement('PIS');

        if (isset($pisItem)) {
            $pis->appendChild($pisItem);
        }

        $this->aPIS[$std->item] = $pis;

        return $pis;
    }

    /**
     * Função que cria a tag PISAliq
     * tag NFe/infNFe/det[]/imposto/PIS/PISAliq
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildPISAliq(\stdClass $std, $identificador)
    {
        // totalizador
        $this->stdTot->vPIS += (float) !empty($std->vPIS) ? $std->vPIS : 0;

        $pisItem = $this->dom->createElement('PISAliq');

        $this->dom->addChild(
            $pisItem,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Código de Situação Tributária do PIS"
        );
        $this->dom->addChild(
            $pisItem,
            'vBC',
            $this->conditionalNumberFormatting($std->vBC),
            true,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do PIS"
        );
        $this->dom->addChild(
            $pisItem,
            'pPIS',
            $this->conditionalNumberFormatting($std->pPIS, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do PIS (em percentual)"
        );
        $this->dom->addChild(
            $pisItem,
            'vPIS',
            $this->conditionalNumberFormatting($std->vPIS),
            true,
            "{$identificador} [item $std->item] Valor do PIS"
        );

        return $pisItem;
    }

    /**
     * Função que cria a tag PISQtde
     * tag NFe/infNFe/det[]/imposto/PIS/PISQtde
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildPISQtde(\stdClass $std, $identificador)
    {
        // totalizador
        $this->stdTot->vPIS += (float) !empty($std->vPIS) ? $std->vPIS : 0;

        $pisItem = $this->dom->createElement('PISQtde');

        $this->dom->addChild(
            $pisItem,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Código de Situação Tributária do PIS"
        );
        $this->dom->addChild(
            $pisItem,
            'qBCProd',
            $this->conditionalNumberFormatting($std->qBCProd, 4),
            true,
            "{$identificador} [item $std->item] Quantidade Vendida"
        );
        $this->dom->addChild(
            $pisItem,
            'vAliqProd',
            $this->conditionalNumberFormatting($std->vAliqProd, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do PIS (em reais)"
        );
        $this->dom->addChild(
            $pisItem,
            'vPIS',
            $this->conditionalNumberFormatting($std->vPIS),
            true,
            "{$identificador} [item $std->item] Valor do PIS"
        );

        return $pisItem;
    }

    /**
     * Função que cria a tag PISNT
     * tag NFe/infNFe/det[]/imposto/PIS/PISNT
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildPISNT(\stdClass $std, $identificador)
    {
        $pisItem = $this->dom->createElement('PISNT');

        $this->dom->addChild(
            $pisItem,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Código de Situação Tributária do PIS"
        );

        return $pisItem;
    }

    /**
     * Função que cria a tag PISOutr
     * tag NFe/infNFe/det[]/imposto/PIS/PISOutr
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildPISOutr(\stdClass $std, $identificador)
    {
        // totalizador
        $this->stdTot->vPIS += (float) !empty($std->vPIS) ? $std->vPIS : 0;

        $pisItem = $this->dom->createElement('PISOutr');

        $this->dom->addChild(
            $pisItem,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Código de Situação Tributária do PIS"
        );
        if (!isset($std->qBCProd)) {
            $this->dom->addChild(
                $pisItem,
                'vBC',
                $this->conditionalNumberFormatting($std->vBC),
                ($std->vBC !== null) ? true : false,
                "{$identificador} [item $std->item] Valor da Base de Cálculo do PIS"
            );
            $this->dom->addChild(
                $pisItem,
                'pPIS',
                $this->conditionalNumberFormatting($std->pPIS, 4),
                ($std->pPIS !== null) ? true : false,
                "{$identificador} [item $std->item] Alíquota do PIS (em percentual)"
            );
        } else {
            $this->dom->addChild(
                $pisItem,
                'qBCProd',
                $this->conditionalNumberFormatting($std->qBCProd, 4),
                ($std->qBCProd !== null) ? true : false,
                "{$identificador} [item $std->item] Quantidade Vendida"
            );
            $this->dom->addChild(
                $pisItem,
                'vAliqProd',
                $this->conditionalNumberFormatting($std->vAliqProd, 4),
                ($std->vAliqProd !== null) ? true : false,
                "{$identificador} [item $std->item] Alíquota do PIS (em reais)"
            );
        }
        $this->dom->addChild(
            $pisItem,
            'vPIS',
            $this->conditionalNumberFormatting($std->vPIS),
            ($std->vPIS !== null) ? true : false,
            "{$identificador} [item $std->item] Valor do PIS"
        );

        return $pisItem;
    }
}
