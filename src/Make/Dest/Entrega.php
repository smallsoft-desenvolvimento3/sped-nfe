<?php

/**
 * Trait Helper para tags relacionados a identificação do local de entrega
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

trait Entrega
{
    /**
     * @var \DOMElement
     */
    protected $entrega;

    /**
     * Identificação do Local de entrega G01 pai A01
     * tag NFe/infNFe/entrega (opcional)
     * NOTA: ajustado para NT 2018.005
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagentrega(\stdClass $std)
    {
        $possible = [
            'xLgr',
            'nro',
            'xCpl',
            'xBairro',
            'cMun',
            'xMun',
            'UF',
            'CNPJ',
            'CPF',
            'xNome',
            'CEP',
            'cPais',
            'xPais',
            'fone',
            'email',
            'IE'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = 'G01 <entrega> - ';

        $this->entrega = $this->dom->createElement("entrega");

        $this->dom->addChild(
            $this->entrega,
            "CNPJ",
            $std->CNPJ,
            false,
            $identificador . "CNPJ do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "CPF",
            $std->CPF,
            false,
            $identificador . "CPF do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "xNome",
            $std->xNome,
            false,
            $identificador . "Nome do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "xLgr",
            $std->xLgr,
            true,
            $identificador . "Logradouro do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "nro",
            $std->nro,
            true,
            $identificador . "Número do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "xCpl",
            $std->xCpl,
            false,
            $identificador . "Complemento do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "xBairro",
            $std->xBairro,
            true,
            $identificador . "Bairro do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "cMun",
            $std->cMun,
            true,
            $identificador . "Código do município do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "xMun",
            $std->xMun,
            true,
            $identificador . "Nome do município do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "UF",
            $std->UF,
            true,
            $identificador . "Sigla da UF do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "CEP",
            $std->CEP,
            false,
            $identificador . "CEP do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "cPais",
            $std->cPais,
            false,
            $identificador . "Codigo do Pais do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "xPais",
            $std->xPais,
            false,
            $identificador . "Pais do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "fone",
            $std->fone,
            false,
            $identificador . "Fone do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "email",
            $std->email,
            false,
            $identificador . "Email do Endereco do Cliente da Entrega"
        );
        $this->dom->addChild(
            $this->entrega,
            "IE",
            $std->IE,
            false,
            $identificador . "IE do Cliente da Entrega"
        );
        return $this->entrega;
    }
}
