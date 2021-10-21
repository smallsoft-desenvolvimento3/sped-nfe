<?php

/**
 * Trait Helper para impostos relacionados a COFINS
 * Esta trait basica está estruturada para montar as tags de COFINS para o
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

trait COFINS
{

    /**
     * @var array<\DOMElement>
     */
    protected $aCOFINS = [];

    /**
     * Grupo COFINS S01 pai M01
     * tag det[item]/imposto/COFINS (opcional)
     * @param  \stdClass $std
     * @return \DOMElement
     */
    public function tagCOFINS(\stdClass $std)
    {
        $possible = [
            'item',
            'CST',
            'vBC',
            'pCOFINS',
            'vCOFINS',
            'qBCProd',
            'vAliqProd'
        ];

        $std = $this->equilizeParameters($std, $possible);

        /**
         * Identificador da Tag para mostrar nos erros
         */
        $identificador = 'S01 <COFINS> -';

        switch ($std->CST) {
            case '01':
            case '02':
                $cofinsItem = $this->buildCOFINSAliq($std, $identificador);
                break;
            case '03':
                $cofinsItem = $this->buildCOFINSQtde($std, $identificador);
                break;
            case '04':
            case '05':
            case '06':
            case '07':
            case '08':
            case '09':
                $cofinsItem = $this->buildCOFINSNT($std, $identificador);
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
                $cofinsItem = $this->buildCOFINSoutr($std, $identificador);
                break;
        }

        $cofins = $this->dom->createElement('COFINS');

        if (isset($cofinsItem)) {
            $cofins->appendChild($cofinsItem);
        }

        $this->aCOFINS[$std->item] = $cofins;
        return $cofins;
    }

    /**
     * Grupo COFINS tributado pela alíquota S02 pai S01
     * tag det/imposto/COFINS/COFINSAliq (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    protected function buildCOFINSAliq(\stdClass $std, $identificador)
    {
        // totalizador
        $this->stdTot->vCOFINS += (float) $std->vCOFINS;

        $cofins = $this->dom->createElement('COFINSAliq');

        $this->dom->addChild(
            $cofins,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Código de Situação Tributária da COFINS"
        );
        $this->dom->addChild(
            $cofins,
            'vBC',
            $this->conditionalNumberFormatting($std->vBC),
            true,
            "{$identificador} [item $std->item] Valor da Base de Cálculo da COFINS"
        );
        $this->dom->addChild(
            $cofins,
            'pCOFINS',
            $this->conditionalNumberFormatting($std->pCOFINS, 4),
            true,
            "{$identificador} [item $std->item] Alíquota da COFINS (em percentual)"
        );
        $this->dom->addChild(
            $cofins,
            'vCOFINS',
            $this->conditionalNumberFormatting($std->vCOFINS),
            true,
            "{$identificador} [item $std->item] Valor da COFINS"
        );
        return $cofins;
    }

    /**
     * Grupo COFINS não tributado S04 pai S01
     * tag NFe/infNFe/det[]/imposto/COFINS/COFINSNT (opcional)
     * @param \stdClass $std
     * @return DOMElement
     */
    protected function buildCOFINSNT(\stdClass $std, $identificador)
    {
        $cofins = $this->dom->createElement('COFINSNT');
        $this->dom->addChild(
            $cofins,
            "CST",
            $std->CST,
            true,
            "{$identificador} [item $std->item] Código de Situação Tributária da COFINS"
        );
        return $cofins;
    }

    /**
     * Grupo COFINS Outras Operações S05 pai S01
     * tag NFe/infNFe/det[]/imposto/COFINS/COFINSoutr (opcional)
     * @param \stdClass $std
     * @return DOMElement
     */
    protected function buildCOFINSoutr(\stdClass $std, $identificador)
    {
        // totalizador
        $this->stdTot->vCOFINS += (float) $std->vCOFINS;

        $cofins = $this->dom->createElement('COFINSOutr');

        $this->dom->addChild(
            $cofins,
            "CST",
            $std->CST,
            true,
            "{$identificador} [item $std->item] Código de Situação Tributária da COFINS"
        );
        if (!isset($std->qBCProd)) {
            $this->dom->addChild(
                $cofins,
                "vBC",
                $this->conditionalNumberFormatting($std->vBC),
                ($std->vBC !== null) ? true : false,
                "{$identificador} [item $std->item] Valor da Base de Cálculo da COFINS"
            );
            $this->dom->addChild(
                $cofins,
                "pCOFINS",
                $this->conditionalNumberFormatting($std->pCOFINS, 4),
                ($std->pCOFINS !== null) ? true : false,
                "{$identificador} [item $std->item] Alíquota da COFINS (em percentual)"
            );
        } else {
            $this->dom->addChild(
                $cofins,
                "qBCProd",
                $this->conditionalNumberFormatting($std->qBCProd, 4),
                ($std->qBCProd !== null) ? true : false,
                "{$identificador} [item $std->item] Quantidade Vendida"
            );
            $this->dom->addChild(
                $cofins,
                "vAliqProd",
                $this->conditionalNumberFormatting($std->vAliqProd, 4),
                ($std->vAliqProd !== null) ? true : false,
                "{$identificador} [item $std->item] Alíquota da COFINS (em reais)"
            );
        }
        $this->dom->addChild(
            $cofins,
            "vCOFINS",
            $this->conditionalNumberFormatting($std->vCOFINS),
            true,
            "{$identificador} [item $std->item] Valor da COFINS"
        );
        return $cofins;
    }

    /**
     * Grupo COFINS Outras Operações S05 pai S01
     * tag NFe/infNFe/det[]/imposto/COFINS/COFINSQtde (opcional)
     * @param \stdClass $std
     * @return DOMElement
     */
    protected function buildCOFINSQtde(\stdClass $std, $identificador)
    {
        //totalizador
        $this->stdTot->vCOFINS += (float) $std->vCOFINS;

        $cofins = $this->dom->createElement('COFINSQtde');

        $this->dom->addChild(
            $cofins,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Código de Situação Tributária da COFINS"
        );
        $this->dom->addChild(
            $cofins,
            'qBCProd',
            $this->conditionalNumberFormatting($std->qBCProd, 4),
            true,
            "{$identificador} [item $std->item] Quantidade Vendida"
        );
        $this->dom->addChild(
            $cofins,
            'vAliqProd',
            $this->conditionalNumberFormatting($std->vAliqProd, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do COFINS (em reais)"
        );
        $this->dom->addChild(
            $cofins,
            'vCOFINS',
            $this->conditionalNumberFormatting($std->vCOFINS),
            true,
            "{$identificador} [item $std->item] Valor do COFINS"
        );
        return $cofins;
    }
}
