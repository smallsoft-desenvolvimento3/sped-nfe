<?php

/**
 * Trait Helper para tags relacionados a Pessoas autorizadas para o download do XML da NF-e
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

trait AutXML
{
    /**
     * @var array<\DOMElement>
     */
    protected $aAutXML = [];

    /**
     * Pessoas autorizadas para o download do XML da NF-e G50 pai A01
     * tag NFe/infNFe/autXML
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagautXML(\stdClass $std)
    {
        $possible = ['CNPJ', 'CPF'];
        $std = $this->equilizeParameters($std, $possible);
        $identificador = 'G50 <autXML> - ';
        $std->CNPJ = !empty($std->CNPJ) ? $std->CNPJ : null;
        $std->CPF = !empty($std->CPF) ? $std->CPF : null;
        $autXML = $this->dom->createElement("autXML");
        $this->dom->addChild(
            $autXML,
            "CNPJ",
            $std->CNPJ,
            false,
            $identificador . "CNPJ do Cliente Autorizado"
        );
        $this->dom->addChild(
            $autXML,
            "CPF",
            $std->CPF,
            false,
            $identificador . "CPF do Cliente Autorizado"
        );
        $this->aAutXML[] = $autXML;
        return $autXML;
    }
}
