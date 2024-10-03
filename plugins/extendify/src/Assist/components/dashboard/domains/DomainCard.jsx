import { __ } from '@wordpress/i18n';
import { chevronRightSmall, Icon } from '@wordpress/icons';
import { safeParseJson } from '@shared/lib/parsing';
import {
	createDomainUrlLink,
	deleteDomainCache,
	domainSearchUrl,
} from '@assist/lib/domains';
import { useTasksStore } from '@assist/state/tasks';

const domains = safeParseJson(window.extSharedData.resourceData)?.domains || [];

export const DomainCard = ({ task }) => {
	const { completeTask } = useTasksStore();

	const handleInteract = () => {
		completeTask(task.slug);
		deleteDomainCache();
	};

	if (!domains?.length) {
		return (
			<div
				className="flex h-full w-full items-center justify-center bg-cover bg-right-bottom bg-no-repeat"
				style={{
					backgroundImage: `url(${task.backgroundImage}})`,
				}}>
				{__('Service offline. Check back later.', 'extendify-local')}
			</div>
		);
	}

	if (!domainSearchUrl) return null;

	return (
		<div
			className="flex h-full w-full bg-cover bg-right-bottom bg-no-repeat"
			data-test="assist-domain-card-main-domain-module"
			style={{ backgroundImage: `url(${task.backgroundImage})` }}>
			<div className="flex w-full flex-col px-8 py-12 md:pl-8 md:pr-0 lg:mr-24">
				<div className="title text-2xl font-semibold md:text-4xl">
					{task.innerTitle}
				</div>
				<div className="description mb-8 mt-2 text-base">
					{task.description}
				</div>
				<div className="overflow-auto rounded bg-gray-100 md:w-full">
					<div className="rounded-tl rounded-tr border-b border-gray-200 px-6 py-4 md:flex md:flex-wrap md:items-center md:justify-between">
						<div>
							<div className="mb-1 w-fit rounded-full border-wp-alert-yellow bg-wp-alert-yellow bg-opacity-40 px-3 py-1 text-xs uppercase text-gray-900">
								{__('Recommended', 'extendify-local')}
							</div>
							<div className="text-xl font-semibold lowercase">
								{domains[0]}
							</div>
						</div>
						<a
							href={createDomainUrlLink(domainSearchUrl, domains[0])}
							target="_blank"
							rel="noreferrer"
							onClick={handleInteract}
							className="mt-3 inline-flex h-8 cursor-pointer items-center justify-between rounded-sm border-design-main bg-design-main px-3 py-2 text-center text-sm leading-tight text-design-text no-underline hover:opacity-90 md:mt-0 md:flex">
							{__('Register this domain', 'extendify-local')}
							<Icon icon={chevronRightSmall} className="fill-current" />
						</a>
					</div>
					{/*Secondary domains*/}
					{domains?.slice(1)?.map((domain) => (
						<a
							href={createDomainUrlLink(domainSearchUrl, domain)}
							target="_blank"
							rel="noreferrer"
							className="flex h-11 cursor-pointer items-center justify-between border-b border-gray-200 px-6 py-3.5 text-sm font-normal lowercase text-gray-800 no-underline last:border-transparent hover:bg-gray-50"
							onClick={handleInteract}
							key={domain}>
							{domain}
							<Icon icon={chevronRightSmall} />
						</a>
					))}
				</div>
			</div>
		</div>
	);
};
