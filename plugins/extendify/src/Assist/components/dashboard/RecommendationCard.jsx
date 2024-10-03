import { Button } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { Icon, check, warning } from '@wordpress/icons';
import { useActivityStore } from '@shared/state/activity';
import { installPlugin } from '@assist/api/WPApi';

export const RecommendationCard = ({ recommendation }) => {
	if (recommendation.pluginSlug) {
		return <InstallCard recommendation={recommendation} />;
	}
	return <LinkCard recommendation={recommendation} />;
};

const LinkCard = ({ recommendation }) => {
	const { by, slug, description, image, title, linkType } = recommendation;
	const { incrementActivity } = useActivityStore();
	if (!recommendation?.[linkType]) return null;

	return (
		<a
			href={recommendation[linkType]?.replace(
				'{APEXDOMAIN}',
				window.extSharedData?.apexDomain ?? '',
			)}
			onClick={() => incrementActivity(`recommendations-${slug}`)}
			target="_blank"
			rel="noopener noreferrer"
			className="cursor-pointer rounded border border-gray-200 bg-transparent p-4 text-left text-base no-underline hover:border-design-main hover:bg-gray-50">
			<div className="h-full w-full">
				<img
					className="h-8 w-8 rounded fill-current"
					alt={
						by ? sprintf(__('Logo for %s', 'extendify-local'), by) : undefined
					}
					src={image}
				/>
				<div className="mt-2 font-semibold">{title}</div>
				{by && <div className="text-sm text-gray-700">{by}</div>}
				<div className="mt-2 text-sm text-gray-800">{description}</div>
			</div>
		</a>
	);
};

const InstallCard = ({ recommendation }) => {
	const { by, slug, description, image, title, pluginSlug } = recommendation;
	const { incrementActivity } = useActivityStore();
	return (
		<div
			onClick={() => incrementActivity(`recommendations-install-${slug}`)}
			className="rounded border border-gray-200 bg-transparent p-4 text-left text-base">
			<div className="h-full w-full">
				<img
					className="h-8 w-8 rounded fill-current"
					alt={
						by ? sprintf(__('Logo for %s', 'extendify-local'), by) : undefined
					}
					src={image}
				/>
				<div className="mt-2 font-semibold">{title}</div>
				{by && <div className="text-sm text-gray-700">{by}</div>}
				<div className="mb-3 mt-2 text-sm text-gray-800">{description}</div>
				<InstallButton pluginSlug={pluginSlug} />
			</div>
		</div>
	);
};

const InstallButton = ({ pluginSlug }) => {
	const [installing, setInstalling] = useState(false);
	const [status, setStatus] = useState('');

	useEffect(() => {
		const { installedPlugins, activePlugins } = window.extSharedData;
		const hasPlugin = (p) => p?.includes(pluginSlug);
		const installed = Object.values(installedPlugins).some(hasPlugin);
		const active = Object.values(activePlugins).some(hasPlugin);
		if (installed) setStatus('inactive');
		if (active) setStatus('active');
	}, [pluginSlug, setStatus]);

	const handleClick = async () => {
		setInstalling(true);
		try {
			await installPlugin(pluginSlug);
			setStatus('active');
		} catch {
			setStatus('error');
			setTimeout(() => {
				setStatus(status);
			}, 1500);
		}
		setInstalling(false);
	};

	if (status === 'error') {
		return (
			<>
				<p
					className="flex items-center fill-wp-alert-red text-wp-alert-red"
					style={{ fontSize: '13px' }}>
					<Icon icon={warning} />
					{__('Error', 'extendify-local')}
				</p>
			</>
		);
	}

	if (status === 'active') {
		return (
			<>
				<p
					className="flex items-center fill-wp-alert-green text-wp-alert-green"
					style={{ fontSize: '13px' }}>
					<Icon icon={check} />
					{__('Active', 'extendify-local')}
				</p>
			</>
		);
	}

	if (status === 'inactive') {
		return (
			<Button
				onClick={handleClick}
				type="button"
				variant="secondary"
				size="compact"
				disabled={installing}
				isBusy={installing}>
				{installing
					? __('Activating...', 'extendify-local')
					: __('Activate', 'extendify-local')}
			</Button>
		);
	}

	return (
		<Button
			onClick={handleClick}
			type="button"
			variant="secondary"
			size="compact"
			disabled={installing}
			isBusy={installing}>
			{installing
				? __('Installing...', 'extendify-local')
				: __('Install Now', 'extendify-local')}
		</Button>
	);
};
