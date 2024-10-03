import { useState, useRef, useEffect, useMemo } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { getPageTemplates } from '@launch/api/DataApi';
import { PagePreview } from '@launch/components/PagePreview';
import { PageSelectButton } from '@launch/components/PageSelectButton';
import { Title } from '@launch/components/Title';
import { useFetch } from '@launch/hooks/useFetch';
import { PageLayout } from '@launch/layouts/PageLayout';
import { pageState } from '@launch/state/factory';
import { useUserSelectionStore } from '@launch/state/user-selections';

export const fetcher = ({ siteType }) => getPageTemplates(siteType);
export const fetchData = (siteType) => ({
	key: 'pages-list',
	siteType: siteType ?? useUserSelectionStore?.getState().siteType,
});

export const state = pageState('Pages', () => ({
	ready: true,
	canSkip: false,
	validation: null,
	onRemove: () => {
		// If the page is removed then clean up the selected pages
		const { pages, remove } = useUserSelectionStore.getState();
		pages.forEach((page) => remove('pages', page));
	},
}));

export const PagesSelect = () => {
	const { data: availablePages, loading } = useFetch(fetchData, fetcher);
	const [previewing, setPreviewing] = useState();
	const [expandMore, setExpandMore] = useState();
	const { pages, remove, removeAll, add, has, style } = useUserSelectionStore();
	const pagePreviewRef = useRef();

	const homePage = useMemo(
		() => ({
			id: 'home-page',
			slug: 'home-page',
			name: __('Home page', 'extendify-local'),
			patterns: style?.patterns
				.map(({ code }) => code)
				.flat()
				.map((code, i) => ({
					name: `pattern-${i}`,
					code,
				})),
		}),
		[style],
	);
	const styleMemo = useMemo(
		() => ({ ...style, patterns: previewing?.patterns || [] }),
		[style, previewing],
	);

	const handlePageToggle = (page) => {
		if (has('pages', page)) {
			remove('pages', page);
			return;
		}
		add('pages', page);
		return setPreviewing(page);
	};

	useEffect(() => {
		// This needs two frames before the code is rendered
		let raf2;
		const id = requestAnimationFrame(() => {
			raf2 = requestAnimationFrame(() => {
				pagePreviewRef?.current?.scrollTo(0, 0);
			});
		});
		return () => {
			cancelAnimationFrame(id);
			cancelAnimationFrame(raf2);
		};
	}, [previewing]);

	useEffect(() => {
		if (previewing) return;
		setPreviewing(homePage);
	}, [previewing, homePage]);

	useEffect(() => {
		if (!availablePages?.recommended) return;
		// On re-load, remove any lingering pages and add the recommended ones
		removeAll('pages');
		availablePages.recommended.forEach((page) => add('pages', page));
	}, [availablePages?.recommended, removeAll, add]);

	return (
		<PageLayout>
			<div className="grow space-y-4 overflow-y-scroll lg:flex lg:space-y-0">
				<div className="l6:px-16 hidden h-full min-h-screen grow overflow-y-hidden bg-gray-100 px-4 pt-0 lg:block lg:min-h-0 lg:pb-0 xl:px-32">
					<div className="flex h-full flex-col">
						<h3 className="my-2 text-center text-base font-medium text-gray-700 lg:my-4 lg:text-lg">
							{previewing?.name}
						</h3>
						<div
							ref={pagePreviewRef}
							className="relative h-full grow overflow-x-hidden rounded-t-lg lg:h-auto lg:overflow-y-scroll">
							{previewing && !loading && (
								<PagePreview ref={pagePreviewRef} style={styleMemo} />
							)}
						</div>
					</div>
				</div>
				<div className="flex w-full flex-col items-center overflow-y-auto px-6 py-8 lg:max-w-lg lg:px-12 lg:py-16">
					<Title
						title={__(
							'Pick the pages to add to your website',
							'extendify-local',
						)}
						description={__(
							'We already selected the most common pages for your type of website.',
							'extendify-local',
						)}
					/>
					<div
						className="flex w-full flex-col gap-4 pb-4"
						data-test="recommended-pages">
						<PageSelectButton
							page={homePage}
							previewing={homePage.id === previewing?.id}
							onPreview={() => setPreviewing(homePage)}
							checked={true}
							forceChecked={true}
							onChange={() => undefined}
						/>
						{availablePages?.recommended?.map((page) => (
							<PageSelectButton
								key={page.id}
								page={page}
								previewing={page.id === previewing?.id}
								onPreview={() => setPreviewing(page)}
								checked={has('pages', page)}
								onChange={() => handlePageToggle(page)}
							/>
						))}
					</div>

					{!expandMore && (
						<div className="flex items-center justify-center">
							<button
								type="button"
								data-test="expand-more"
								onClick={setExpandMore}
								className="button-focus my-4 cursor-pointer bg-transparent text-center text-sm font-medium text-gray-900 hover:text-design-main">
								{__('View more pages', 'extendify-local')}
							</button>
						</div>
					)}

					{expandMore && (
						<div
							className="flex w-full flex-col gap-4 pb-4"
							data-test="optional-pages">
							{availablePages?.optional?.map((page) => (
								<PageSelectButton
									key={page.id}
									page={page}
									previewing={page.id === previewing?.id}
									onPreview={() => setPreviewing(page)}
									checked={pages?.some((p) => p.id === page.id)}
									onChange={() => handlePageToggle(page)}
								/>
							))}
						</div>
					)}
				</div>
			</div>
		</PageLayout>
	);
};
