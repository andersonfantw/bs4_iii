<?php

namespace Piwik\Plugins\Diagnostics;

use Piwik\Plugins\Diagnostics\Diagnostic\Diagnostic;
use Piwik\Plugins\Diagnostics\Diagnostic\DiagnosticResult;

/**
 * Runs the Piwik diagnostics.
 *
 * @api
 */
class DiagnosticService
{
    /**
     * @var Diagnostic[]
     */
    private $mandatoryDiagnostics;

    /**
     * @var Diagnostic[]
     */
    private $optionalDiagnostics;

    /**
     * @param Diagnostic[] $mandatoryDiagnostics
     * @param Diagnostic[] $optionalDiagnostics
     * @param Diagnostic[] $disabledDiagnostics
     */
    public function __construct(array $mandatoryDiagnostics, array $optionalDiagnostics, array $disabledDiagnostics)
    {
        $this->mandatoryDiagnostics = $this->removeDisabledDiagnostics($mandatoryDiagnostics, $disabledDiagnostics);
        $this->optionalDiagnostics = $this->removeDisabledDiagnostics($optionalDiagnostics, $disabledDiagnostics);
    }

    /**
     * @return DiagnosticReport
     */
    public function runDiagnostics()
    {
        return new DiagnosticReport(
            $this->run($this->mandatoryDiagnostics),
            $this->run($this->optionalDiagnostics)
        );
    }

    /**
     * @param Diagnostic[] $diagnostics
     * @return DiagnosticResult[]
     */
    private function run(array $diagnostics)
    {
        $results = array();

        foreach ($diagnostics as $diagnostic) {
            $results = array_merge($results, $diagnostic->execute());
        }

        return $results;
    }

    private function removeDisabledDiagnostics(array $diagnostics, array $disabledDiagnostics)
    {
        return array_filter($diagnostics, function (Diagnostic $diagnostic) use ($disabledDiagnostics) {
            return ! in_array($diagnostic, $disabledDiagnostics, true);
        });
    }
}
