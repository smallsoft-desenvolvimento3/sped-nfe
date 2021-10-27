<?php

/**
 * Trait Helper para tags relacionados ao Grupo de informações de exportação
 *
 * Essa trait é dependente da \NFePHP\NFe\Make\Det\DetExport
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

use NFePHP\Common\Strings;

trait DetExportInd
{
    /**
     * Grupo de informações de exportação para o item I52 pai I52
     * tag NFe/infNFe/det[]/prod/detExport
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagdetExportInd(\stdClass $std)
    {
        $possible = [
            'item',
            'nRE',
            'chNFe',
            'qExport'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = "I52 <exportInd> - [item $std->item]:";

        $exportInd = $this->dom->createElement('exportInd');

        $this->dom->addChild(
            $exportInd,
            "nRE",
            Strings::onlyNumbers($std->nRE),
            true,
            "{$identificador} Número do Registro de Exportação"
        );
        $this->dom->addChild(
            $exportInd,
            "chNFe",
            Strings::onlyNumbers($std->chNFe),
            true,
            "{$identificador} Chave de Acesso da NF-e recebida para exportação"
        );
        $this->dom->addChild(
            $exportInd,
            "qExport",
            $this->conditionalNumberFormatting($std->qExport, 4),
            true,
            "{$identificador} Quantidade do item realmente exportado"
        );
        //obtem o ultimo detExport
        // $nDE = count($this->aDetExport[$std->item]) - 1;
        // if ($nDE < 0) {
        $nDE = $this->aDetExport[$std->item];
        if (!$nDE) {
            throw new \RuntimeException('A TAG detExportInd deve ser criada depois da detExport, pois pertence a ela.');
        }
        //colocar a exportInd em seu DetExport respectivo
        $nodeDetExport = $this->aDetExport[$std->item][$nDE];
        $this->dom->appChild($nodeDetExport, $exportInd);
        $this->aDetExport[$std->item][$nDE] = $nodeDetExport;
        return $exportInd;
    }
}
