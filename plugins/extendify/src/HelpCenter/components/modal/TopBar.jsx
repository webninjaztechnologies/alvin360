import { __ } from '@wordpress/i18n';
import { Icon, closeSmall, chevronLeft, reset } from '@wordpress/icons';
import { useActivityStore } from '@shared/state/activity';
import classNames from 'classnames';
import { useRouter } from '@help-center/hooks/useRouter';
import { useGlobalSyncStore } from '@help-center/state/globals-sync';

const { partnerLogo, partnerName } = window.extSharedData;

export const Topbar = () => {
	const { visibility, setVisibility } = useGlobalSyncStore();
	const { incrementActivity } = useActivityStore();
	const { current, history } = useRouter();
	const handleClose = () => {
		incrementActivity(`hc-close-button-when-${visibility}`);
		setVisibility('closed');
	};
	const isMinimized = visibility === 'minimized';
	const toggleMinimized = () => {
		const nextState = isMinimized ? 'open' : 'minimized';
		incrementActivity(`hc-toggle-button-${nextState}`);
		setVisibility(nextState);
	};

	return (
		<div className="relative flex items-center justify-end gap-x-2 bg-banner-main p-4">
			<div
				role={isMinimized ? 'button' : 'heading'}
				onClick={isMinimized ? toggleMinimized : undefined}
				aria-label={
					isMinimized ? __('Show Help Center', 'extendify-local') : undefined
				}
				aria-expanded={isMinimized ? 'false' : 'true'}
				className={classNames('flex w-full justify-between bg-banner-main', {
					'cursor-pointer': isMinimized,
				})}>
				<div
					className={classNames('flex w-full gap-1', {
						'gap-4': history.length === 1,
					})}>
					<LogoOrBackButton />
					{current?.title && (
						<span className="border-banner-text text-base font-medium text-banner-text">
							{current.title}
						</span>
					)}
				</div>
			</div>
			<div className="flex items-center justify-end gap-2.5">
				<button
					className="m-0 cursor-pointer border-0 bg-transparent fill-banner-text p-0 text-banner-text"
					type="button"
					data-test="help-center-toggle-minimize-button"
					onClick={toggleMinimized}>
					{isMinimized ? (
						<>
							<Icon
								className="rotate-90 fill-current"
								icon={chevronLeft}
								size={24}
							/>
							<span className="sr-only">
								{__('Show Help Center', 'extendify-local')}
							</span>
						</>
					) : (
						<>
							<Icon className="fill-current" icon={reset} size={24} />
							<span className="sr-only">
								{__('Minimize Help Center', 'extendify-local')}
							</span>
						</>
					)}
				</button>
				<button
					className="m-0 cursor-pointer border-0 bg-transparent fill-banner-text p-0 text-banner-text"
					type="button"
					data-test="help-center-close-button"
					onClick={handleClose}>
					<Icon icon={closeSmall} size={24} />
					<span className="sr-only">{__('close', 'extendify-local')}</span>
				</button>
			</div>
		</div>
	);
};

const LogoOrBackButton = () => {
	const { goBack, history } = useRouter();
	const { visibility } = useGlobalSyncStore();

	if (history.length > 1 && visibility === 'open') {
		return (
			<button
				className="m-0 cursor-pointer border-0 bg-transparent fill-banner-text p-0 text-banner-text"
				type="button"
				onClick={goBack}>
				<Icon icon={chevronLeft} />
				<span className="sr-only">{__('Go back', 'extendify-local')}</span>
			</button>
		);
	}

	return partnerLogo ? (
		<div className="flex h-6 justify-center bg-banner-main after:relative after:-right-2 after:top-0.5 after:mr-2 after:text-banner-text after:opacity-40 after:content-['|']">
			<div className="flex h-6 max-w-[9rem] overflow-hidden">
				<img
					className="max-h-full max-w-full object-contain"
					src={partnerLogo}
					alt={partnerName}
				/>
			</div>
		</div>
	) : null;
};
