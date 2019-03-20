using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AMU.Dto
{
    public class BandGage
    {
        public Band Band { get; set; }
        public string Gage { get; set; } = "";
        public DateTime Record_Date { get; set; }
        public override string ToString()
        {
            return Band.Name;
        }
    }
}
