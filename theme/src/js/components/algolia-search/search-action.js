export default function () {
    return {
        indexSearch: function (query, index) {
            const message = $('.search-results > .message');
            let listedResults = $('.search-results > .results');

            index.search({query: query, distinct: 2, facets: ['*']}, (err, content) => {
                if (err) {
                    message.text(`There was an error. Please try again. Error details follow: ${err}`);
                } else if (content.hits.length === 0) {
                    listedResults.empty();
                    message.text('No results');
                } else {
                    listedResults.empty();
                    message.text('');
                    $.each(content.hits, (key, result) => {
                        const resultItem = `<div class='result-item'>${result.objectTitle}</div>`;
                        listedResults.append(resultItem);
                    });
                }
            });
        }
    };
}
