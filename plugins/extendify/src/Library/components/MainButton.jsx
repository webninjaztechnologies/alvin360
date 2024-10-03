import { __ } from '@wordpress/i18n';
import { Icon } from '@wordpress/icons';
import { useActivityStore } from '@shared/state/activity';
import { extendifyLogo } from '@library/icons/extendify-logo';
import { useGlobalsStore } from '@library/state/global';

export const MainButton = () => {
	const { setOpen } = useGlobalsStore();
	const { incrementActivity } = useActivityStore();

	const handleClick = () => {
		// Minimize HC if its open
		window.dispatchEvent(new CustomEvent('extendify-hc:minimize'));
		setOpen(true);
		incrementActivity('library-button-click');
	};

	return (
		<div
			role="button"
			onClick={handleClick}
			className="components-button has-icon is-primary h-8 min-w-0 cursor-pointer px-1 xs:h-9 xs:pl-2 xs:pr-3 sm:ml-2">
			<Icon icon={extendifyLogo} size={24} />
			<span className="ml-1 hidden xs:inline">
				{__('Design Library', 'extendify-local')}
			</span>
		</div>
	);
};
