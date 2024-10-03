import { __ } from '@wordpress/i18n';
import { HelpCenterMarkup } from '@assist/tasks/images/HelpCenterMarkup';

export default {
	slug: 'help-center',
	title: __('Help Center', 'extendify-local'),
	sidebarTitle: __('Learn about the Help Center', 'extendify-local'),
	description: __(
		'Get instant support, explore our Knowledge Base, or take guided tours to make the most of our tools.',
		'extendify-local',
	),
	buttonLabels: {
		completed: __('Revisit', 'extendify-local'),
		notCompleted: __('Explore Help Center', 'extendify-local'),
	},
	type: 'html-text-button',
	event: new CustomEvent('extendify-hc:open'),
	dependencies: { goals: [], plugins: [] },
	show: () => !window.extSharedData?.aiChatEnabled,
	htmlBefore: () => (
		<HelpCenterMarkup
			className="border-gray300 pointer-events-none relative hidden h-56 w-full overflow-hidden rounded-t-lg border bg-gray-100 pt-5 md:mb-8 lg:block"
			aria-hidden="true"
		/>
	),
};
