<?php

/**
 * Trait Helper para tags relacionados a identificação do local de retirada
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

trait Retirada
{
    /**
     * @var \DOMElement
     */
    protected $retirada;

    /**
     * Identificação do Local de retirada F01 pai A01
     * tag NFe/infNFe/retirada (opcional)
     * NOTA: ajustado para NT 2018.005
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagretirada(\stdClass $std)
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

        $identificador = 'F01 <retirada> - ';

        $this->retirada = $this->dom->createElement("retirada");

        $this->dom->addChild(
            $this->retirada,
            "CNPJ",
            $std->CNPJ,
            false,
            $identificador . "CNPJ do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "CPF",
            $std->CPF,
            false,
            $identificador . "CPF do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "xNome",
            $std->xNome,
            false,
            $identificador . "Nome do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "xLgr",
            $std->xLgr,
            true,
            $identificador . "Logradouro do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "nro",
            $std->nro,
            true,
            $identificador . "Número do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "xCpl",
            $std->xCpl,
            false,
            $identificador . "Complemento do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "xBairro",
            $std->xBairro,
            true,
            $identificador . "Bairro do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "cMun",
            $std->cMun,
            true,
            $identificador . "Código do município do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "xMun",
            $std->xMun,
            true,
            $identificador . "Nome do município do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "UF",
            $std->UF,
            true,
            $identificador . "Sigla da UF do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "CEP",
            $std->CEP,
            false,
            $identificador . "CEP do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "cPais",
            $std->cPais,
            false,
            $identificador . "Codigo do Pais do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "xPais",
            $std->xPais,
            false,
            $identificador . "Pais do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "fone",
            $std->fone,
            false,
            $identificador . "Fone do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "email",
            $std->email,
            false,
            $identificador . "Email do Endereco do Cliente da Retirada"
        );
        $this->dom->addChild(
            $this->retirada,
            "IE",
            $std->IE,
            false,
            $identificador . "IE do Cliente da Retirada"
        );
        return $this->retirada;
    }
}
