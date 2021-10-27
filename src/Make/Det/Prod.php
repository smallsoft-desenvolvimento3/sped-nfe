<?php

/**
 * Trait Helper para tags relacionados ao detalhamento de itens
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

use NFePHP\NFe\Common\Gtin;

trait Prod
{
    /**
     * @var array<\DOMElement>
     */
    protected $aProd = [];

    /**
     * Detalhamento de Produtos e Serviços I01 pai H01
     * tag NFe/infNFe/det[]/prod
     * NOTA: Ajustado para NT2016_002_v1.30
     * NOTA: Ajustado para NT2020_005_v1.20
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagprod(\stdClass $std)
    {
        $possible = [
            'item',
            'cProd',
            'cEAN',
            'cBarra',
            'xProd',
            'NCM',
            'cBenef',
            'EXTIPI',
            'CFOP',
            'uCom',
            'qCom',
            'vUnCom',
            'vProd',
            'cEANTrib',
            'cBarraTrib',
            'uTrib',
            'qTrib',
            'vUnTrib',
            'vFrete',
            'vSeg',
            'vDesc',
            'vOutro',
            'indTot',
            'xPed',
            'nItemPed',
            'nFCI'
        ];

        $std = $this->equilizeParameters($std, $possible);
        // totalizador
        if ($std->indTot == 1) {
            $this->stdTot->vProd += (float) $this->conditionalNumberFormatting($std->vProd);
        }

        $this->stdTot->vFrete += (float) $this->conditionalNumberFormatting($std->vFrete);
        $this->stdTot->vSeg += (float) $this->conditionalNumberFormatting($std->vSeg);
        $this->stdTot->vDesc += (float) $this->conditionalNumberFormatting($std->vDesc);
        $this->stdTot->vOutro += (float) $this->conditionalNumberFormatting($std->vOutro);

        $cean = !empty($std->cEAN) ? trim(strtoupper($std->cEAN)) : '';
        $ceantrib = !empty($std->cEANTrib) ? trim(strtoupper($std->cEANTrib)) : '';

        //throw exception if not is Valid
        try {
            Gtin::isValid($cean);
        } catch (\InvalidArgumentException $e) {
            $this->errors[] = "cEANT {$cean} " . $e->getMessage();
        }

        try {
            Gtin::isValid($ceantrib);
        } catch (\InvalidArgumentException $e) {
            $this->errors[] = "cEANTrib {$ceantrib} " . $e->getMessage();
        }

        $identificador = "I01 <prod> - [item $std->item]:";

        $prod = $this->dom->createElement('prod');

        $this->dom->addChild(
            $prod,
            "cProd",
            $std->cProd,
            true,
            "Código do produto ou serviço"
        );
        $this->dom->addChild(
            $prod,
            "cEAN",
            $cean,
            true,
            "{$identificador} GTIN (Global Trade Item Number) do produto, antigo "
                . "código EAN ou código de barras",
            true
        );
        $this->dom->addChild(
            $prod,
            "cBarra",
            $std->cBarra ?? null,
            false,
            "{$identificador} cBarra Código de barras diferente do padrão GTIN"
        );
        $xProd = $std->xProd;
        if ($this->tpAmb == '2' && $this->mod == '65' && $std->item === 1) {
            $xProd = 'NOTA FISCAL EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL';
        }
        $this->dom->addChild(
            $prod,
            "xProd",
            $xProd,
            true,
            "{$identificador} Descrição do produto ou serviço"
        );
        $this->dom->addChild(
            $prod,
            "NCM",
            $std->NCM,
            true,
            "{$identificador} Código NCM com 8 dígitos ou 2 dígitos (gênero)"
        );
        //incluido no layout 4.00
        $this->dom->addChild(
            $prod,
            "cBenef",
            $std->cBenef,
            false,
            "{$identificador} Código de Benefício Fiscal utilizado pela UF"
        );
        $this->dom->addChild(
            $prod,
            "EXTIPI",
            $std->EXTIPI,
            false,
            "{$identificador} Preencher de acordo com o código EX da TIPI"
        );
        $this->dom->addChild(
            $prod,
            "CFOP",
            $std->CFOP,
            true,
            "{$identificador} Código Fiscal de Operações e Prestações"
        );
        $this->dom->addChild(
            $prod,
            "uCom",
            $std->uCom,
            true,
            "{$identificador} Unidade Comercial do produto"
        );
        $this->dom->addChild(
            $prod,
            "qCom",
            $this->conditionalNumberFormatting($std->qCom, 4),
            true,
            "{$identificador} Quantidade Comercial do produto"
        );
        $this->dom->addChild(
            $prod,
            "vUnCom",
            $this->conditionalNumberFormatting($std->vUnCom, 10),
            true,
            "{$identificador} Valor Unitário de Comercialização do produto"
        );
        $this->dom->addChild(
            $prod,
            "vProd",
            $this->conditionalNumberFormatting($std->vProd),
            true,
            "{$identificador} Valor Total Bruto dos Produtos ou Serviços"
        );
        $this->dom->addChild(
            $prod,
            "cEANTrib",
            $ceantrib,
            true,
            "{$identificador} GTIN (Global Trade Item Number) da unidade tributável, antigo "
                . "código EAN ou código de barras",
            true
        );
        $this->dom->addChild(
            $prod,
            "cBarraTrib",
            $std->cBarraTrib ?? null,
            false,
            "{$identificador} cBarraTrib Código de Barras da "
                . "unidade tributável que seja diferente do padrão GTIN"
        );
        $this->dom->addChild(
            $prod,
            "uTrib",
            $std->uTrib,
            true,
            "{$identificador} Unidade Tributável do produto"
        );
        $this->dom->addChild(
            $prod,
            "qTrib",
            $this->conditionalNumberFormatting($std->qTrib, 4),
            true,
            "{$identificador} Quantidade Tributável do produto"
        );
        $this->dom->addChild(
            $prod,
            "vUnTrib",
            $this->conditionalNumberFormatting($std->vUnTrib, 10),
            true,
            "{$identificador} Valor Unitário de tributação do produto"
        );
        $this->dom->addChild(
            $prod,
            "vFrete",
            $this->conditionalNumberFormatting($std->vFrete),
            false,
            "{$identificador} Valor Total do Frete"
        );
        $this->dom->addChild(
            $prod,
            "vSeg",
            $this->conditionalNumberFormatting($std->vSeg),
            false,
            "{$identificador} Valor Total do Seguro"
        );
        $this->dom->addChild(
            $prod,
            "vDesc",
            $this->conditionalNumberFormatting($std->vDesc),
            false,
            "{$identificador} Valor do Desconto"
        );
        $this->dom->addChild(
            $prod,
            "vOutro",
            $this->conditionalNumberFormatting($std->vOutro),
            false,
            "{$identificador} Outras despesas acessórias"
        );
        $this->dom->addChild(
            $prod,
            "indTot",
            $std->indTot,
            true,
            "{$identificador} Indica se valor do Item (vProd) entra no valor total da NF-e (vProd)"
        );
        $this->dom->addChild(
            $prod,
            "xPed",
            $std->xPed,
            false,
            "{$identificador} Número do Pedido de Compra"
        );
        $this->dom->addChild(
            $prod,
            "nItemPed",
            $std->nItemPed,
            false,
            "{$identificador} Item do Pedido de Compra"
        );
        $this->dom->addChild(
            $prod,
            "nFCI",
            $std->nFCI,
            false,
            "{$identificador} Número de controle da FCI "
                . "Ficha de Conteúdo de Importação"
        );
        $this->aProd[$std->item] = $prod;
        return $prod;
    }
}
