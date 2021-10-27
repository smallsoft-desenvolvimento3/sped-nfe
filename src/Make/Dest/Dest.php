<?php

/**
 * Trait Helper para tags relacionados a identificação do destinatário
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

namespace NFePHP\NFe\Make\Dest;

use NFePHP\Common\Strings;

trait Dest
{
    /**
     * @var \DOMElement
     */
    protected $dest;

    /**
     * Identificação do Destinatário da NF-e E01 pai A01
     * tag NFe/infNFe/dest (opcional para modelo 65)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagdest(\stdClass $std)
    {
        $possible = [
            'xNome',
            'indIEDest',
            'IE',
            'ISUF',
            'IM',
            'email',
            'CNPJ',
            'CPF',
            'idEstrangeiro'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $identificador = 'E01 <dest> - ';
        $flagNome = true; //marca se xNome é ou não obrigatório
        $temIE = $std->IE != '' && $std->IE != 'ISENTO'; // Tem inscrição municipal
        $this->dest = $this->dom->createElement("dest");
        if (!$temIE && $std->indIEDest == 1) {
            $std->indIEDest = 2;
        }
        if ($this->mod == '65') {
            $std->indIEDest = 9;
            if ($std->xNome == '') {
                $flagNome = false; //marca se xNome é ou não obrigatório
            }
        }
        $xNome = $std->xNome;
        if ($this->tpAmb == '2' && !empty($xNome)) {
            $xNome = 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL';
            //a exigência do CNPJ 99999999000191 não existe mais
        } elseif ($this->tpAmb == '2' && $this->mod == '65') {
            $xNome = 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL';
        }
        if (!empty($std->CNPJ)) {
            $this->dom->addChild(
                $this->dest,
                "CNPJ",
                Strings::onlyNumbers($std->CNPJ),
                true,
                $identificador . "CNPJ do destinatário"
            );
        } elseif (!empty($std->CPF)) {
            $this->dom->addChild(
                $this->dest,
                "CPF",
                Strings::onlyNumbers($std->CPF),
                true,
                $identificador . "CPF do destinatário"
            );
        } elseif ($std->idEstrangeiro !== null) {
            $this->dom->addChild(
                $this->dest,
                "idEstrangeiro",
                $std->idEstrangeiro,
                true,
                $identificador . "Identificação do destinatário no caso de comprador estrangeiro",
                true
            );
            $std->indIEDest = '9';
        }
        $this->dom->addChild(
            $this->dest,
            "xNome",
            substr(trim($xNome), 0, 60),
            $flagNome, //se mod 55 true ou mod 65 false
            $identificador . "Razão Social ou nome do destinatário"
        );
        $this->dom->addChild(
            $this->dest,
            "indIEDest",
            Strings::onlyNumbers($std->indIEDest),
            true,
            $identificador . "Indicador da IE do Destinatário"
        );
        if ($temIE) {
            $this->dom->addChild(
                $this->dest,
                "IE",
                $std->IE,
                true,
                $identificador . "Inscrição Estadual do Destinatário"
            );
        }
        $this->dom->addChild(
            $this->dest,
            "ISUF",
            Strings::onlyNumbers($std->ISUF),
            false,
            $identificador . "Inscrição na SUFRAMA do destinatário"
        );
        $this->dom->addChild(
            $this->dest,
            "IM",
            Strings::onlyNumbers($std->IM),
            false,
            $identificador . "Inscrição Municipal do Tomador do Serviço do destinatário"
        );
        $this->dom->addChild(
            $this->dest,
            "email",
            substr(trim($std->email), 0, 60),
            false,
            $identificador . "Email do destinatário"
        );
        return $this->dest;
    }
}
