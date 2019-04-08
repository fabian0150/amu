using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AMU.Dto
{
    public class Offer
    {
        public int ID { get; set; }
        public int LocationID { get; set; } 
        public int UserID { get; set; }
        public string OfferDate { get; set; }
        public string VeranstaltungName { get; set; }
        public DateTime Record_Date { get; set; }
        public override string ToString()
        {
            return VeranstaltungName;
        }
    }
}
