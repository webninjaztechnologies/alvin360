import { serialize, pasteHandler } from '@wordpress/blocks';
import { useEffect, useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Icon } from '@wordpress/icons';
import classNames from 'classnames';
import { Error } from '@help-center/components/ai-chat/Error';
import { Rating } from '@help-center/components/ai-chat/Rating';
import { robot, send } from '@help-center/components/ai-chat/icons';
import { useAIChatStore } from '@help-center/state/ai-chat';

export const Answer = ({ question, answer, reset, error, answerId }) => {
	const scrollRef = useRef(null);
	const { addHistory, setCurrentQuestion } = useAIChatStore();

	// check https://github.com/extendify/extendify-sdk/issues/1560
	const parsedAnswer = pasteHandler({
		plainText: answer?.replace(/[\r\n]+/g, '<br />') ?? '',
	});
	const htmlAnswer = Array.isArray(parsedAnswer)
		? serialize(parsedAnswer)
		: parsedAnswer;

	useEffect(() => {
		if (!answerId) return;
		const newQuestion = { answerId, htmlAnswer, question, time: Date.now() };
		addHistory(newQuestion);
		setCurrentQuestion(newQuestion);
	}, [answerId, htmlAnswer, addHistory, question, setCurrentQuestion]);

	if (error) {
		return (
			<div className="overflow-y-auto p-6 pb-10" ref={scrollRef}>
				<div className="relative mb-8 ml-4 flex justify-end">
					<Error
						text={__(
							'Oops! We were unable to send your question.',
							'extendify-local',
						)}
						reset={reset}
					/>
				</div>
			</div>
		);
	}

	return (
		<div className="flex h-full flex-col">
			<div className="flex-grow overflow-y-auto p-6 pb-10" ref={scrollRef}>
				<div className="relative mb-8 ml-4 flex justify-end">
					<div className="m-0 rounded-lg bg-gray-800 p-5 text-sm text-design-text">
						{question}
					</div>
				</div>
				<div className="relative">
					<div className="absolute z-10 -ml-2 -mt-4 flex items-center rounded-full bg-design-main p-2">
						<Icon
							icon={robot}
							className="h-4 w-4 fill-current text-design-text"
						/>
					</div>
					<div
						className={classNames(
							'm-0 inline-block rounded-lg bg-gray-100 p-5 text-sm text-gray-800',
							{
								'animate-pulse bg-gray-300': answer === '...',
								'bg-gray-100': answer !== '...',
							},
						)}
						dangerouslySetInnerHTML={{
							__html: htmlAnswer,
						}}
					/>
					{answerId && <Rating answerId={answerId} />}
				</div>
			</div>
			<div className="ask-another-question relative flex justify-center p-4">
				<button
					type="button"
					onClick={reset}
					className="flex cursor-pointer items-center gap-2 rounded-sm border-none bg-design-main px-4 py-2 text-sm text-design-text">
					{__('Ask Another Question', 'extendify-local')}
					<Icon icon={send} className="h-6 fill-current text-design-text" />
				</button>
			</div>
		</div>
	);
};
