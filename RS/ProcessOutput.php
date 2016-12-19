<?php
namespace RS;

use RS\Converter;
use RS\Error;
use RS\FormatInput;
use RS\FormattedPattern;
use RS\ProcessedOutput;
use RS\Serial;

class ProcessOutput {

	public function __construct(FormatInput $formatInput, Converter $converter, Error $error) {
		$this->formatInput = $formatInput;
		$this->converter = $converter;
		$this->error = $error;

	}

	/**
	 * { format Request from controller to prepare it for processing }
	 *
	 * @param      <Request>  $request  The request
	 *
	 * @return     <string>  ( processed serial for controller  )
	 */

	public function processResponse($request) {

		$processedResponse;
		$formattedPattern = $this->formatInput->giveMeFormatPattern($request->pattern);

		if ($formattedPattern instanceof FormattedPattern) {
			$processedResponse = $this->functionToCallAfterReceivingFormattedPattern($request, $formattedPattern);

		} else {
			$processedResponse = new ProcessedOutput(null, "failed", "pattern is not defined as required", 400);

		}

		return $processedResponse;

	}

	/**
	 * {  it will genererate a FormattedSerial and then the pattern will go through thorough validation
	 * 	  and if it passes then it will process the serial,otherwise it will generate an error
	 * 	  ProcessedOutput object      }
	 *
	 * @param      <Request>           $request           The request
	 * @param      <FormattedPattern>           $formattedPattern  The formatted pattern
	 *
	 * @return     ProcessedOutput  ( it will return the processed output  )
	 */

	public function functionToCallAfterReceivingFormattedPattern($request, $formattedPattern) {

		$formattedSerial = $this->formatInput->giveMeFormatSerial($request->serial);

		$PatternConventionError = $this->error->patternSerialValidation($formattedPattern, $formattedSerial->serial);

		$dateConventionError = $this->error->dateValidationLogic($formattedPattern->date, $formattedSerial->date);

		if (!$PatternConventionError) {
			$processedResponse = new ProcessedOutput(null, "failed", "Pattern and Serial doesn't match according to the convention specified", 400);

		} else if (!$dateConventionError) {

			$processedResponse = new ProcessedOutput(null, "failed", "datePattern and date convention doesn't match", 400);

		} else {

			$processedSerial = $this->converter->processSerial($formattedPattern, $formattedSerial->serial);
			$processedResponse = new ProcessedOutput($processedSerial, "success", "Pattern and serial convention is right", 200);

		}

		return $processedResponse;

	}

}