using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AMU.Dto
{
    public class Band
    {
        public int? ID { get; set; }
        public string Name { get; set; }
        public string Logo_Path { get; set; }
        public string Website_Url { get; set; }
        public int? Leader_ID { get; set; }
        public string Leader_Username { get; set; }
        public DateTime Record_Date { get; set; }

        public override string ToString()
        {
            return Name;
        }
    }
}
