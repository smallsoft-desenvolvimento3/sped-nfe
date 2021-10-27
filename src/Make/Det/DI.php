<?php

/**
 * Trait Helper para tags relacionados a Declaração de Importação
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

trait DI
{
    /**
     * @var array<\DOMElement>
     */
    protected $aDI = [];

    /**
     * Declaração de Importação I8 pai I01
     * tag NFe/infNFe/det[]/prod/DI
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagDI(\stdClass $std)
    {
        $possible = [
            'item',
            'nDI',
            'dDI',
            'xLocDesemb',
            'UFDesemb',
            'dDesemb',
            'tpViaTransp',
            'vAFRMM',
            'tpIntermedio',
            'CNPJ',
            'UFTerceiro',
            'cExportador'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = "I8 <DI> - [item $std->item]:";

        $tDI = $this->dom->createElement('DI');

        $this->dom->addChild(
            $tDI,
            "nDI",
            $std->nDI,
            true,
            "{$identificador} Número do Documento de Importação (DI, DSI, DIRE, ...)"
        );
        $this->dom->addChild(
            $tDI,
            "dDI",
            $std->dDI,
            true,
            "{$identificador} Data de Registro do documento"
        );
        $this->dom->addChild(
            $tDI,
            "xLocDesemb",
            $std->xLocDesemb,
            true,
            "{$identificador} Local de desembaraço"
        );
        $this->dom->addChild(
            $tDI,
            "UFDesemb",
            $std->UFDesemb,
            true,
            "{$identificador} Sigla da UF onde ocorreu o Desembaraço Aduaneiro"
        );
        $this->dom->addChild(
            $tDI,
            "dDesemb",
            $std->dDesemb,
            true,
            "{$identificador} Data do Desembaraço Aduaneiro"
        );
        $this->dom->addChild(
            $tDI,
            "tpViaTransp",
            $std->tpViaTransp,
            true,
            "{$identificador} Via de transporte internacional "
                . "informada na Declaração de Importação (DI)"
        );
        $this->dom->addChild(
            $tDI,
            "vAFRMM",
            $this->conditionalNumberFormatting($std->vAFRMM),
            false,
            "{$identificador} Valor da AFRMM "
                . "- Adicional ao Frete para Renovação da Marinha Mercante"
        );
        $this->dom->addChild(
            $tDI,
            "tpIntermedio",
            $std->tpIntermedio,
            true,
            "{$identificador} Forma de importação quanto a intermediação"
        );
        $this->dom->addChild(
            $tDI,
            "CNPJ",
            $std->CNPJ,
            false,
            "{$identificador} CNPJ do adquirente ou do encomendante"
        );
        $this->dom->addChild(
            $tDI,
            "UFTerceiro",
            $std->UFTerceiro,
            false,
            "{$identificador} Sigla da UF do adquirente ou do encomendante"
        );
        $this->dom->addChild(
            $tDI,
            "cExportador",
            $std->cExportador,
            true,
            "{$identificador} Código do Exportador"
        );
        $this->aDI[$std->item][$std->nDI] = $tDI;
        return $tDI;
    }
}
