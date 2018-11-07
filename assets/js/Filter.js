import React, {Component} from 'react'
import AsyncSelect from 'react-select/lib/Async'

class Filter extends Component {
    tags = [];
    tagsUrl = this.props.tagsData.url;
    selectedTags = [];

    filterTags = (inputValue) => {
        return this.tags.filter(i =>
            i.label.toLowerCase().includes(inputValue.toLowerCase())
        );
    };

    loadOptions = (inputValue, callback) => {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', this.tagsUrl + '?name=' + inputValue);
        xhr.send();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status !== 200) {
                    alert(xhr.status + ': ' + xhr.statusText);
                } else {
                    if (xhr.response !== '[]') {
                        const data = JSON.parse(xhr.response);
                        for (let tagIndex in data) {
                            let tagName = data[tagIndex].name;
                            const newTag = {value: tagName, label: tagName};

                            function tagExists(newTag, tags) {
                                for (let tagIndex in tags) {
                                    if (tags[tagIndex].value === newTag.value) {
                                        return true;
                                    }
                                }
                                return false;
                            }

                            if (!tagExists(newTag, this.tags)) {
                                this.tags.push(newTag);
                            }
                        }
                    }
                }
            }
        }.bind(this);

        setTimeout(() => {
            callback(this.filterTags(inputValue));
        }, 1000);
    };

    updateSelectedTags = (selectedOptions) => {
        this.selectedTags = this.tags.filter(tag => {
            for (let optionIndex in selectedOptions) {
                if (selectedOptions[optionIndex].value.toLowerCase() === tag.value.toLowerCase()) {
                    return true;
                }
            }
            return false;
        });
    };

    mapTagsValue = () => this.selectedTags.map((row) => {
        return row.value
    });

    refreshData = () => {
        this.props.setTags(this.mapTagsValue(), () => { this.props.refreshData({}) });
    };

    render() {
        return (
            <div className="pure-form">
                <legend>Выберите тэги для фильтрации по ним</legend>
                <AsyncSelect isMulti cacheOptions defaultOptions loadOptions={this.loadOptions}
                             onChange={this.updateSelectedTags}
                             className="m-b" placeholder="Введите тег"/>
                <button onClick={this.refreshData}
                        className="pure-button filter-btn m-b">Отфильтровать
                </button>
            </div>
        );
    }
    ;
}

export default Filter;