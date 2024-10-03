import { __ } from '@wordpress/i18n';
import { HelpCenterAIMarkup } from '@assist/tasks/images/HelpCenterAIMarkup';

export default {
	slug: 'help-center-ai',
	title: __('AI-Powered Help Center', 'extendify-local'),
	sidebarTitle: __('Experience the AI-Powered Help Center', 'extendify-local'),
	description: __(
		'Get instant support with Co-Pilot AI, explore our Knowledge Base, or take guided tours to make the most of our tools.',
		'extendify-local',
	),
	buttonLabels: {
		completed: __('Revisit', 'extendify-local'),
		notCompleted: __('Explore Help Center', 'extendify-local'),
	},
	type: 'html-text-button',
	event: new CustomEvent('extendify-hc:open', { detail: { page: 'ai-chat' } }),
	dependencies: { goals: [], plugins: [] },
	show: () => !!window.extSharedData?.aiChatEnabled,
	htmlBefore: () => (
		<HelpCenterAIMarkup
			className="border-gray300 pointer-events-none relative hidden h-56 w-full overflow-hidden rounded-t-lg border bg-gray-100 pt-5 md:mb-8 lg:block"
			aria-hidden="true"
		/>
	),
};
