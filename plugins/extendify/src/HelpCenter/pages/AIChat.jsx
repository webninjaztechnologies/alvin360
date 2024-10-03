import { useLayoutEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { chevronRight, Icon, postComments } from '@wordpress/icons';
import { AnimatePresence, motion } from 'framer-motion';
import { Answer } from '@help-center/components/ai-chat/Answer';
import { History } from '@help-center/components/ai-chat/History';
import { Nav } from '@help-center/components/ai-chat/Nav';
import { Question } from '@help-center/components/ai-chat/Question';
import { Support } from '@help-center/components/ai-chat/Support';
import { getAnswer } from '@help-center/lib/api';
import { updateUserMeta } from '@help-center/lib/wp';
import { useAIChatStore } from '@help-center/state/ai-chat';

export const AIChatDashboard = ({ onOpen }) => {
	return (
		<section className="">
			<button
				type="button"
				onClick={onOpen}
				className="m-0 flex w-full cursor-pointer justify-between gap-2 rounded-md border border-gray-200 bg-transparent p-2.5 text-left hover:bg-gray-100">
				<Icon
					icon={postComments}
					className="rounded-full border-0 bg-design-main fill-design-text p-2"
					size={48}
				/>
				<div className="grow pl-1">
					<h1 className="m-0 p-0 text-lg font-medium">
						{__('Ask AI', 'extendify-local')}
					</h1>
					<p className="m-0 p-0 text-xs text-gray-800">
						{__('Got questions? Ask our AI chatbot', 'extendify-local')}
					</p>
				</div>
				<div className="flex h-12 grow-0 items-center justify-end">
					<Icon
						icon={chevronRight}
						size={24}
						className="fill-current text-gray-700"
					/>
				</div>
			</button>
		</section>
	);
};

export const AIChat = () => {
	const [question, setQuestion] = useState(undefined);
	const [answer, setAnswer] = useState(undefined);
	const [answerId, setAnswerId] = useState(undefined);
	const [error, setError] = useState(false);

	const [showHistory, setShowHistory] = useState(false);
	const { experienceLevel, currentQuestion, setCurrentQuestion } =
		useAIChatStore();

	const showAIConsent = window.extSharedData?.showAIConsent;
	const [userGaveConsent, setUserGaveConsent] = useState(
		window.extSharedData?.userGaveConsent,
	);

	const reset = () => {
		setQuestion(undefined);
		setAnswer(undefined);
		setAnswerId(undefined);
		setError(false);
		setShowHistory(false);
		setCurrentQuestion(undefined);
	};

	const handleSubmit = async (formSubmitEvent) => {
		formSubmitEvent.preventDefault();
		const q = formSubmitEvent.target?.[0]?.value ?? '';
		if (!q) return;
		setAnswer('...');
		setQuestion(q);
		const response = await getAnswer({ question: q, experienceLevel });
		if (!response.ok) {
			setError(true);
			return;
		}
		try {
			const reader = response.body.getReader();
			const decoder = new TextDecoder();
			while (true) {
				const { value, done } = await reader.read();
				if (done) break;
				const chunk = decoder.decode(value);
				setAnswer((v) => {
					if (v === '...') return chunk;
					// For bw compatability we remove the json appended to the end
					return (v + chunk).replace(/\{"id":"[a-zA-Z0-9]+"\}/g, '');
				});
			}
			setAnswerId(response.headers.get('x-extendify-chat-id') || undefined);
		} catch (e) {
			console.error(e);
		}
	};

	useLayoutEffect(() => {
		setQuestion(currentQuestion?.question);
		setAnswer(currentQuestion?.htmlAnswer);
		setShowHistory(false);
	}, [currentQuestion]);

	if (showAIConsent && !userGaveConsent) {
		return (
			<ConsentOverlay
				onAccept={() => {
					updateUserMeta('ai_consent', true);
					setUserGaveConsent(true);
				}}
			/>
		);
	}

	if (question) {
		return (
			<Answer
				question={question}
				answer={answer}
				answerId={answerId}
				reset={reset}
				error={error}
			/>
		);
	}

	return (
		<>
			<section className="flex h-full flex-col">
				<Nav setShowHistory={setShowHistory} showHistory={showHistory} />
				<div className="flex flex-grow items-center bg-design-main p-6 text-design-text">
					<Question onSubmit={handleSubmit} />
				</div>
				<Support height={'h-11'} />
			</section>
			<AnimatePresence>
				{showHistory && (
					<motion.section
						// slide up from bottom 100%
						initial={{ x: 50 }}
						animate={{ x: 0 }}
						exit={{ x: 0 }}
						transition={{ duration: 0.2 }}
						style={{ '--ext-design-text': '#000000' }}
						className="absolute bottom-0 left-0 right-0 top-0 z-20 ml-4 mt-4 flex h-full flex-col overflow-hidden rounded-tl-lg bg-white shadow-2xl">
						<History setShowHistory={setShowHistory} />
					</motion.section>
				)}
			</AnimatePresence>
		</>
	);
};

const ConsentOverlay = ({ onAccept }) => (
	<div className="absolute inset-0 flex items-center justify-center bg-black/75 p-6">
		<div className="rounded bg-white p-4">
			<h2 className="mb-2 mt-0 text-lg">
				{__('Terms of Use', 'extendify-local')}
			</h2>
			<p
				className="m-0"
				dangerouslySetInnerHTML={{
					__html: window.extSharedData.consentTermsHTML,
				}}></p>
			<button
				className="mt-4 w-full cursor-pointer rounded border-0 bg-design-main px-4 py-2 text-center text-white"
				type="button"
				onClick={onAccept}>
				{__('Accept', 'extendify-local')}
			</button>
		</div>
	</div>
);

export const routes = [
	{
		slug: 'ai-chat',
		title: __('AI Chatbot', 'extendify-local'),
		component: AIChat,
	},
];
