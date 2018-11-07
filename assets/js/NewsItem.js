import React, {Component} from 'react'
import {
    FacebookIcon,
    TwitterIcon,
    VKIcon,
    OKIcon,
    FacebookShareButton,
    VKShareButton,
    OKShareButton,
    TwitterShareButton,
} from 'react-share';

const Tags = (props) => {
    let tagItems = props.tags.map((tag) =>
        <span key={tag} className="button-secondary pure-button">
            {tag}
        </span>
    );

    return (
        tagItems
    );
};

class NewsItem extends Component {
    newsItem = JSON.parse(this.props.newsItem);

    render() {
        const {name, text, image, tags} = this.newsItem;
        const url = window.location.href;
        const shareButtonSize = 32;
        let imageStyle = {
            backgroundImage: 'url(' + image + ')'
        };

        return (
            <div>
                <div className="clearfix">
                    <h1>{name}</h1>
                    <div className="image-container">
                        <div style={imageStyle} className="image">
                        </div>
                    </div>
                    <p>{text}</p>
                </div>
                <div>
                    <p>Тэги:</p>
                    <Tags tags={tags}/>
                </div>
                <div>
                    <p>Поделиться:</p>
                    <FacebookShareButton className="social-button" url={url}>
                        <FacebookIcon size={shareButtonSize} round={true}/>
                    </FacebookShareButton>
                    <VKShareButton className="social-button" url={url}>
                        <VKIcon size={shareButtonSize} round={true}/>
                    </VKShareButton>
                    <OKShareButton className="social-button" url={url}>
                        <OKIcon size={shareButtonSize} round={true}/>
                    </OKShareButton>
                    <TwitterShareButton className="social-button" url={url}>
                        <TwitterIcon size={shareButtonSize} round={true}/>
                    </TwitterShareButton>
                </div>
            </div>
        );
    }
}

export default NewsItem;