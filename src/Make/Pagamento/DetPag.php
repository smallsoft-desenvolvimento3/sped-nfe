<?php

/**
 * Trait Helper para tags relacionados ao detalhamento do pagamento
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

namespace NFePHP\NFe\Make\Pagamento;

trait DetPag
{

    /**
     * Grupo de Formas de Pagamento YA01a pai YA01
     * NOTA: Ajuste NT_2016_002_v1.30
     * NOTA: Ajuste NT_2016_002_v1 51
     * NOTA: Ajuste NT_2020_006
     * tag NFe/infNFe/pag/detPag
     * @param stdClass $std
     * @return DOMElement
     */
    public function tagdetPag($std)
    {
        $possible = [
            'indPag',
            'tPag',
            'xPag',
            'vPag',
            'CNPJ',
            'tBand',
            'cAut',
            'tpIntegra'
        ];
        $std = $this->equilizeParameters($std, $possible);
        //padrão para layout 4.00
        $detPag = $this->dom->createElement("detPag");
        $this->dom->addChild(
            $detPag,
            "indPag",
            $std->indPag,
            false,
            "Indicador da Forma de Pagamento"
        );
        $this->dom->addChild(
            $detPag,
            "tPag",
            $std->tPag,
            true,
            "Forma de pagamento"
        );
        $this->dom->addChild(
            $detPag,
            "xPag",
            !empty($std->xPag) ? $std->xPag : null,
            false,
            "Descricao da Forma de pagamento"
        );
        $this->dom->addChild(
            $detPag,
            "vPag",
            $this->conditionalNumberFormatting($std->vPag),
            true,
            "Valor do Pagamento"
        );
        if (!empty($std->tpIntegra)) {
            $card = $this->dom->createElement("card");
            $this->dom->addChild(
                $card,
                "tpIntegra",
                $std->tpIntegra,
                true,
                "Tipo de Integração para pagamento"
            );
            $this->dom->addChild(
                $card,
                "CNPJ",
                !empty($std->CNPJ) ? $std->CNPJ : null,
                false,
                "CNPJ da Credenciadora de cartão de crédito e/ou débito"
            );
            $this->dom->addChild(
                $card,
                "tBand",
                !empty($std->tBand) ? $std->tBand : null,
                false,
                "Bandeira da operadora de cartão de crédito e/ou débito"
            );
            $this->dom->addChild(
                $card,
                "cAut",
                !empty($std->cAut) ? $std->cAut : null,
                false,
                "Número de autorização da operação cartão de crédito e/ou débito"
            );
            $this->dom->appChild($detPag, $card, "Inclusão do node Card");
        }
        $node = !empty($this->pag->getElementsByTagName("vTroco")->item(0))
            ? $this->pag->getElementsByTagName("vTroco")->item(0)
            : null;
        if (!empty($node)) {
            $this->pag->insertBefore($detPag, $node);
        } else {
            $this->dom->appChild($this->pag, $detPag, 'Falta tag "Pag"');
        }
        return $detPag;
    }
}
