<?php

declare(strict_types=1);

namespace OpenAI\Responses\Threads\Runs\Steps\Delta;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Responses\Threads\Runs\ThreadRunResponseLastError;
use OpenAI\Responses\Threads\Runs\Steps\ThreadRunStepResponseToolCallsStepDetails;
use OpenAI\Responses\Threads\Runs\Steps\ThreadRunStepResponseMessageCreationStepDetails;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @implements ResponseContract<array{type: string, text: array{value: string, annotations: array<int, array{type: string, text: string, file_citation: array{file_id: string, quote: string}, start_index: int, end_index: int}|array{type: string, text: string, file_path: array{file_id: string}, start_index: int, end_index: int}>}}>
 */
final class ThreadRunStepDeltaObject implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{type: string, text: array{value: string, annotations: array<int, array{type: string, text: string, file_citation: array{file_id: string, quote: string}, start_index: int, end_index: int}|array{type: string, text: string, file_path: array{file_id: string}, start_index: int, end_index: int}>}}>
     */
    use ArrayAccessible;

    use Fakeable;

    private function __construct(
        public ThreadRunStepResponseMessageCreationStepDetails|ThreadRunStepResponseToolCallsStepDetails $stepDetails,
    ) {
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array{type: string, text: array{value: string, annotations: array<int, array{type: 'file_citation', text: string, file_citation: array{file_id: string, quote: string}, start_index: int, end_index: int}|array{type: 'file_path', text: string, file_path: array{file_id: string}, start_index: int, end_index: int}>}}  $attributes
     */
    public static function from(array $attributes): self
    {
        $stepDetails = match ($attributes['step_details']['type']) {
            'message_creation' => ThreadRunStepResponseMessageCreationStepDetails::from($attributes['step_details']),
            'tool_calls' => ThreadRunStepResponseToolCallsStepDetails::from($attributes['step_details']),
        };
        return new self(
            $attributes['step_details'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'step_details' => $this->stepDetails,
        ];
    }
}
