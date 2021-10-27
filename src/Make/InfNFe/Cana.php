<?php

/**
 * Trait Helper para tags relacionados a Cana; Fornecimento diário de cana; Grupo Deduções – Taxas e Contribuições de cana
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

namespace NFePHP\NFe\Make\InfNFe;

trait Cana
{
    /**
     * @var \DOMElement
     */
    protected $cana;

    /**
     * Grupo Cana ZC01 pai A01
     * tag NFe/infNFe/cana (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagcana(\stdClass $std)
    {
        $possible = [
            'safra',
            'ref',
            'qTotMes',
            'qTotAnt',
            'qTotGer',
            'vFor',
            'vTotDed',
            'vLiqFor'
        ];
        $std = $this->equilizeParameters($std, $possible);

        /**
         * Identificador da Tag para mostrar nos erros
         */
        $identificador = 'ZC01 <cana> -';

        $this->cana = $this->dom->createElement("cana");

        $this->dom->addChild(
            $this->cana,
            "safra",
            $std->safra,
            true,
            "{$identificador} Identificação da safra"
        );
        $this->dom->addChild(
            $this->cana,
            "ref",
            $std->ref,
            true,
            "{$identificador} Mês e ano de referência"
        );
        $this->dom->addChild(
            $this->cana,
            "qTotMes",
            $std->qTotMes,
            true,
            "{$identificador} Quantidade Total do Mês"
        );
        $this->dom->addChild(
            $this->cana,
            "qTotAnt",
            $std->qTotAnt,
            true,
            "{$identificador} Quantidade Total Anterior"
        );
        $this->dom->addChild(
            $this->cana,
            "qTotGer",
            $std->qTotGer,
            true,
            "{$identificador} Quantidade Total Geral"
        );
        $this->dom->addChild(
            $this->cana,
            "vFor",
            $this->conditionalNumberFormatting($std->vFor),
            true,
            "{$identificador} Valor dos Fornecimentos"
        );
        $this->dom->addChild(
            $this->cana,
            "vTotDed",
            $this->conditionalNumberFormatting($std->vTotDed),
            true,
            "{$identificador} Valor Total da Dedução"
        );
        $this->dom->addChild(
            $this->cana,
            "vLiqFor",
            $this->conditionalNumberFormatting($std->vLiqFor),
            true,
            "{$identificador} Valor Líquido dos Fornecimentos"
        );
        return $this->cana;
    }

    /**
     * Grupo Fornecimento diário de cana ZC04 pai ZC01
     * tag NFe/infNFe/cana/forDia
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagforDia(\stdClass $std)
    {
        $forDia = $this->dom->createElement("forDia");

        /**
         * Identificador da Tag para mostrar nos erros
         */
        $identificador = 'ZC04 <forDia> -';

        $forDia->setAttribute("dia", $std->dia);
        $this->dom->addChild(
            $forDia,
            "qtde",
            $this->conditionalNumberFormatting($std->qtde, 10),
            true,
            "${identificador} Quantidade"
        );
        $qTotMes = $this->cana->getElementsByTagName('qTotMes')->item(0);
        $this->cana->insertBefore($forDia, $qTotMes);
        return $forDia;
    }

    /**
     * Grupo Deduções – Taxas e Contribuições ZC10 pai ZC01
     * tag NFe/infNFe/cana/deduc (opcional)
     * @param stdClass $std
     * @return DOMElement
     */
    public function tagdeduc(\stdClass $std)
    {
        $possible = ['xDed', 'vDed'];
        $std = $this->equilizeParameters($std, $possible);
        $deduc = $this->dom->createElement("deduc");

        /**
         * Identificador da Tag para mostrar nos erros
         */
        $identificador = 'ZC10 <deduc> -';

        $this->dom->addChild(
            $deduc,
            "xDed",
            $std->xDed,
            true,
            "{$identificador} Descrição da Dedução"
        );
        $this->dom->addChild(
            $deduc,
            "vDed",
            $this->conditionalNumberFormatting($std->vDed),
            true,
            "{$identificador} Valor da Dedução"
        );
        $vFor = $this->cana->getElementsByTagName('vFor')->item(0);
        $this->cana->insertBefore($deduc, $vFor);
        return $deduc;
    }
}
