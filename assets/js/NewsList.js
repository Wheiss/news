import React, {Component} from 'react'
import Filter from '../js/Filter'

const TableHeader = () => {
    return (
        <thead>
        <tr>
            <th>Название</th>
            <th>Дата</th>
        </tr>
        </thead>
    );
}

const TableBody = props => {
    const rows = props.newsData.map((row, index) => {
        return (
            <tr key={index}>
                <td><a href={row.link}>{row.name}</a></td>
                <td>{row.date}</td>
            </tr>
        );
    });

    return <tbody>{rows}</tbody>;
}

const Paginator = props => {
    const pagesData = props.pagesData;
    const pages = [];

    const currentPage = parseInt(pagesData.currentPage);

    const paginationOffset = 3;
    for (let pageNum = 1; pageNum <= pagesData.totalPages; pageNum++) {
        if(pageNum === 1 || (currentPage-paginationOffset < pageNum && pageNum < currentPage + paginationOffset)
            || pageNum === pagesData.totalPages) {

            let className = "pure-menu-item" + (currentPage === pageNum ? ' pure-menu-selected' : '');
            let buttonClass = "pure-menu-link pure-button" + (currentPage === pageNum ? ' pure-button-disabled' : '');
            pages.push(
                <li key={pageNum} className={className}>
                    <button onClick={() => props.refreshData({page: pageNum})} className={buttonClass}>
                        {pageNum}
                    </button>
                </li>
            );
        }
    }
    return (
        <div className="pure-menu pure-menu-horizontal">
            <ul className="pure-menu-list">
                {pages}
            </ul>
        </div>
    );
}

class NewsList extends Component {
    data = JSON.parse(this.props.news);
    state = {
        newsData: this.data.news,
        pagesData: this.data.paginatorData,
        tagsData: this.data.tagsData,
        currentPage: this.data.paginatorData.currentPage
    };

    setTags = (tags, callback) => {
        this.setState({
            tagsData: {
                tags: tags
            }
        }, callback);
    };

    refreshData = data => {
        if (data.page) {
            this.setState({
                currentPage: data.page
            });
        }
        let page = data.page ? data.page : this.state.currentPage;
        let xhr = new XMLHttpRequest();
        let url = this.state.pagesData.url + '?page=' + page;
        if (this.state.tagsData.tags) {
            let tags = this.state.tagsData.tags;
            for (let tagIndex in tags) {
                url += '&tags[]=' + tags[tagIndex];
            }
        }
        xhr.open('GET', url);
        xhr.send();

        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) return;
            if (xhr.status !== 200) {
                alert(xhr.status + ': ' + xhr.statusText);
            } else {
                const data = xhr.response;
                const localData = JSON.parse(data);
                this.setState({newsData: localData.news});
                this.setState({pagesData: localData.paginatorData});
            }
        }.bind(this);
    }

    render() {
        const {newsData, pagesData, data, tagsData} = this.state;
        return (
            <div>
                <h3>Список новостей</h3>
                <Filter tagsData={tagsData} refreshData={this.refreshData} setTags={this.setTags}/>
                <table className="pure-table table">
                    <TableHeader/>
                    <TableBody newsData={newsData}/>
                </table>
                <Paginator pagesData={pagesData} refreshData={this.refreshData} data={data}/>
            </div>
        );
    }
}

export default NewsList;