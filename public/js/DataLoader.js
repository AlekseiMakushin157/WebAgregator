/**
 * Filling news element with data
 * @param {jQuery} target
 * @param {array} data
 * @returns {null}
 */
function fillNews(target, data) {
    const newsList = $(target).find('.js__news-list');
    const Item = ({ title, url, desc, author, publicationDate, copyright }) => `
        <div class="container card js__news-item">
            <div class="container">
                <h3>
                    <a href="${url}">
                        <b>${title}</b>
                    </a>
                </h3>
                <h5>${author}, <span>${publicationDate}</span></h5>
                <div class="container">
                    <p>${desc}</p>
                    <div>
                        <div>
                            <p><span class="float-right"><b>${copyright}</b></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
`;
    newsList.html(data.map(Item).join(''));
}

/**
 * Show message window
 * @param {String} message
 * @returns {null}
 */
function showMessage(message) {
    alert(message);
}

/**
 * Load data from server
 * @param {String} source
 * @param {Number} limit
 * @param {Number} offset
 * @param {jQuery} target
 * @returns {null}
 */
function loadNews(source, limit, offset, target) {
    $.ajax({
        url: "/index/load", //the page containing php script
        type: "get", //request type,
        dataType: 'json',
        data: {source: source, limit: Math.max(limit, 0), offset: Math.max(offset, 0)},
        beforeSend: function () {
            target.prop("disabled", true);
            target.addClass("disabled");
        },
        success: function (result) {
            const prevBtn = $(target).find(".js__news-prev-btn");
            const nextBtn = $(target).find(".js__news-next-btn");

            const loadedCount = Number(offset) + result.length;

            fillNews(target, result);

            if (result.length >= limit) {
                prevBtn.removeClass("hidden");
            } else {
                prevBtn.addClass("hidden");
            }
            if (offset > 0) {
                nextBtn.removeClass("hidden");
            } else {
                nextBtn.addClass("hidden");
            }

            $(target).attr('source', source);
            $(target).attr('step', limit);
            $(target).attr('loadedCount', loadedCount);
        },
        error: function () {
            showMessage('Не удалось загрузить данные. Попробуйте позже.');
        },
        complete: function () {
            target.prop("disabled", false);
            target.removeClass("disabled");
        }
    });
}

$(document).ready(function () {
    const loadBtn = $('.js__load-btn');
    const nextBtn = $('.js__news-next-btn');
    const prevBtn = $('.js__news-prev-btn');
    const newsBlock = $('.js__news-block');

    /**
     * Request server and get data from selected source
     */
    loadBtn.click(function (e) {
        const source = $('.js__select-source').val();
        const limit = $('.js__select-number').val();
        loadNews(source, limit, 0, newsBlock);
    });

    /**
     * Load older data from server with previous data source and step
     */
    nextBtn.click(function (e) {
        const source = $(newsBlock).attr("source");
        const limit = Number($(newsBlock).attr("step"));
        const offset = Number($(newsBlock).attr("loadedCount"));
        loadNews(source, limit, offset - 2 * limit, newsBlock);
    });

    /**
     * Load newer data from server with previous data source and step
     */
    prevBtn.click(function (e) {
        const source = $(newsBlock).attr("source");
        const limit = $(newsBlock).attr("step");
        const offset = $(newsBlock).attr("loadedCount");
        loadNews(source, limit, offset, newsBlock);
    });
});