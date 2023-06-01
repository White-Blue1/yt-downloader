using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using VideoLibrary;

namespace Mp3YTConverter
{
    internal class YouTube
    {


        public static YouTubeVideo getVideoInformations(string link)
        {
            var youTube = VideoLibrary.YouTube.Default;
            var video = youTube.GetVideo(link);         
            return video;
        }    
        

        public static void AudioConvert(string fileToConvert,string fileToConvertTo,string fileFormat)
        {
            var convert = new NReco.VideoConverter.FFMpegConverter();
            convert.ConvertMedia(fileToConvert, fileToConvertTo, fileFormat);
        }
     
    }
}
