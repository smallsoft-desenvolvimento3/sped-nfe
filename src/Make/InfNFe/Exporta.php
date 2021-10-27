<?php

/**
 * Trait Helper para tags relacionados a Exportação
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

trait Exporta
{

    /**
     * @var \DOMElement
     */
    protected $exporta;

    /**
     * Grupo Exportação ZA01 pai A01
     * tag NFe/infNFe/exporta (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagexporta(\stdClass $std)
    {
        $possible = ['UFSaidaPais', 'xLocExporta', 'xLocDespacho'];
        $std = $this->equilizeParameters($std, $possible);
        $this->exporta = $this->dom->createElement("exporta");
        $this->dom->addChild(
            $this->exporta,
            "UFSaidaPais",
            $std->UFSaidaPais,
            true,
            "Sigla da UF de Embarque ou de transposição de fronteira"
        );
        $this->dom->addChild(
            $this->exporta,
            "xLocExporta",
            $std->xLocExporta,
            true,
            "Descrição do Local de Embarque ou de transposição de fronteira"
        );
        $this->dom->addChild(
            $this->exporta,
            "xLocDespacho",
            $std->xLocDespacho,
            false,
            "Descrição do local de despacho"
        );
        return $this->exporta;
    }
}
