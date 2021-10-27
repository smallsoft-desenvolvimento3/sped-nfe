<?php

/**
 * Trait Helper para tags relacionados a Informações da NF-e
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

trait InfNFe
{
    /**
     * @var \DOMElement
     */
    protected $infNFe;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    public $chNFe;

    /**
     * Informações da NF-e A01 pai NFe
     * tag NFe/infNFe
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function taginfNFe(\stdClass $std)
    {
        $possible = ['Id', 'versao', 'pk_nItem'];
        $std = $this->equilizeParameters($std, $possible);

        $chave = preg_replace('/[^0-9]/', '', $std->Id);

        $this->infNFe = $this->dom->createElement("infNFe");
        $this->infNFe->setAttribute("Id", 'NFe' . $chave);
        $this->infNFe->setAttribute(
            "versao",
            $std->versao
        );
        $this->version = $std->versao;

        if (!empty($std->pk_nItem)) {
            $this->infNFe->setAttribute("pk_nItem", $std->pk_nItem);
        }
        $this->chNFe = $chave;

        return $this->infNFe;
    }
}
