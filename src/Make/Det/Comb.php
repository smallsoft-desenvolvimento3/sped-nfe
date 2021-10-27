<?php

/**
 * Trait Helper para tags relacionados ao detalhamento de combustíveis e encerrante
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

trait Comb
{
    /**
     * @var array<\DOMElement>
     */
    protected $aComb = [];

    /**
     * @var array<\DOMElement>
     */
    protected $aEncerrante = [];

    /**
     * Detalhamento de combustiveis L101 pai I90
     * tag NFe/infNFe/det[]/prod/comb (opcional)
     * LA|cProdANP|pMixGN|CODIF|qTemp|UFCons|
     *
     * NOTA: Ajustado para NT2016_002_v1.30
     * LA|cProdANP|descANP|pGLP|pGNn|pGNi|vPart|CODIF|qTemp|UFCons|
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagcomb(\stdClass $std)
    {
        $possible = [
            'item',
            'cProdANP',
            'descANP',
            'pGLP',
            'pGNn',
            'pGNi',
            'vPart',
            'CODIF',
            'qTemp',
            'UFCons',
            'qBCProd',
            'vAliqProd',
            'vCIDE'
        ];

        $std = $this->equilizeParameters($std, $possible);

        $identificador = "L101 <comb> - [item $std->item]:";

        $comb = $this->dom->createElement('comb');

        $this->dom->addChild(
            $comb,
            "cProdANP",
            $std->cProdANP,
            true,
            "$identificador Código de produto da ANP"
        );
        //incluso no layout 4.00
        $this->dom->addChild(
            $comb,
            "descANP",
            $std->descANP,
            false,
            "$identificador Utilizar a descrição de produtos do "
                . "Sistema de Informações de Movimentação de Produtos - "
                . "SIMP (http://www.anp.gov.br/simp/"
        );
        //incluso no layout 4.00
        $this->dom->addChild(
            $comb,
            "pGLP",
            $this->conditionalNumberFormatting($std->pGLP, 4),
            false,
            "$identificador Percentual do GLP derivado do "
                . "petróleo no produto GLP (cProdANP=210203001) 1v4"
        );
        //incluso no layout 4.00
        $this->dom->addChild(
            $comb,
            "pGNn",
            $this->conditionalNumberFormatting($std->pGNn, 4),
            false,
            "$identificador Percentual de Gás Natural Nacional"
                . " – GLGNn para o produto GLP (cProdANP=210203001) 1v4"
        );
        //incluso no layout 4.00
        $this->dom->addChild(
            $comb,
            "pGNi",
            $this->conditionalNumberFormatting($std->pGNi, 4),
            false,
            "$identificador Percentual de Gás Natural Importado"
                . " – GLGNi para o produto GLP (cProdANP=210203001) 1v4"
        );
        //incluso no layout 4.00
        $this->dom->addChild(
            $comb,
            "vPart",
            $this->conditionalNumberFormatting($std->vPart),
            false,
            "$identificador Valor de partida (cProdANP=210203001) "
        );
        $this->dom->addChild(
            $comb,
            "CODIF",
            $std->CODIF,
            false,
            "[item $std->item] Código de autorização / registro do CODIF"
        );
        $this->dom->addChild(
            $comb,
            "qTemp",
            $this->conditionalNumberFormatting($std->qTemp, 4),
            false,
            "$identificador Quantidade de combustível faturada à temperatura ambiente."
        );
        $this->dom->addChild(
            $comb,
            "UFCons",
            $std->UFCons,
            true,
            "[item $std->item] Sigla da UF de consumo"
        );
        if ($std->qBCProd != "") {
            $tagCIDE = $this->dom->createElement("CIDE");
            $this->dom->addChild(
                $tagCIDE,
                "qBCProd",
                $this->conditionalNumberFormatting($std->qBCProd, 4),
                true,
                "$identificador BC da CIDE"
            );
            $this->dom->addChild(
                $tagCIDE,
                "vAliqProd",
                $this->conditionalNumberFormatting($std->vAliqProd, 4),
                true,
                "$identificador Valor da alíquota da CIDE"
            );
            $this->dom->addChild(
                $tagCIDE,
                "vCIDE",
                $this->conditionalNumberFormatting($std->vCIDE),
                true,
                "$identificador Valor da CIDE"
            );
            $this->dom->appChild($comb, $tagCIDE);
        }
        $this->aComb[$std->item] = $comb;
        return $comb;
    }

    /**
     * informações relacionadas com as operações de combustíveis, subgrupo de
     * encerrante que permite o controle sobre as operações de venda de combustíveis
     * LA11 pai LA01
     * tag NFe/infNFe/det[]/prod/comb/encerrante (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagencerrante(\stdClass $std)
    {
        $possible = [
            'item',
            'nBico',
            'nBomba',
            'nTanque',
            'vEncIni',
            'vEncFin'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = "LA11 <encerrante> - [item $std->item]:";

        $encerrante = $this->dom->createElement('encerrante');

        $this->dom->addChild(
            $encerrante,
            "nBico",
            $std->nBico,
            true,
            "{$identificador} Número de identificação do bico utilizado no abastecimento"
        );
        $this->dom->addChild(
            $encerrante,
            "nBomba",
            $std->nBomba,
            false,
            "{$identificador} Número de identificação da bomba ao qual o bico está interligado"
        );
        $this->dom->addChild(
            $encerrante,
            "nTanque",
            $std->nTanque,
            true,
            "{$identificador} Número de identificação do tanque ao qual o bico está interligado"
        );
        $this->dom->addChild(
            $encerrante,
            "vEncIni",
            $this->conditionalNumberFormatting($std->vEncIni, 3),
            true,
            "{$identificador} Valor do Encerrante no início do abastecimento"
        );
        $this->dom->addChild(
            $encerrante,
            "vEncFin",
            $this->conditionalNumberFormatting($std->vEncFin, 3),
            true,
            "{$identificador} Valor do Encerrante no final do abastecimento"
        );
        $this->aEncerrante[$std->item] = $encerrante;
        return $encerrante;
    }
}
