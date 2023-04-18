import { Page, Layout, LegacyCard, Grid, Button } from '@shopify/polaris';
import React, { useEffect,useState } from "react";
import instance from '../../../shopify/instance';


export default function Plan() {

    const [plans, setPlan] = useState([]);
    const [user, setUser] = useState([]);

    useEffect(() => {
        getPlans();
        getUser(window.currentUser);
    }, []);

    const onPayClickHandle = (event) => {

        var planIndex = event.currentTarget.id;

        instance({
            url: _APP_URL+'api/planchange',
            method: "POST",
            data:{plan:planIndex,name:window.currentUser}
        })
        .then((res) => {
            const conformationUrl = res.data.data.confirmation_url;
            window.top.location.href = conformationUrl;
        })
        .catch((err) => {
            console.log(err);
        });
    }

    const getPlans = () => {
        instance({
            url: _APP_URL+'api/planlist',
            method: "GET"
        })
        .then((res) => {
            setPlan(res.data.data);
        })
        .catch((err) => {
            console.log(err);
        });
    };

    const getUser = (shopName) =>{
        instance({
            url: `${_APP_URL}api/getUser/${shopName}`,
            method: "GET"
        })
        .then((res) => {
            setUser(res.data.data);
        })
        .catch((err) => {
            console.log(err);
        });
    }

  return (

    <Page fullWidth title='Plan Page'>
        <Layout>

            <Layout.Section>
                <LegacyCard title="Billing Plans" sectioned>
                    <Grid>
                        {
                            plans.map((plan,index)=>{
                                return <Grid.Cell key={index} columnSpan={{ xs: 3, sm: 3, md: 3, lg: 3, xl: 4 }}>
                                    <LegacyCard  title={plan.name} sectioned>
                                            <h1 className='card-heading'><span>$</span>{plan.price}</h1><br/><br/>
                                            <p>{plan.terms}</p><br/><br/>

                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</p>
                                            <br /><br />

                                            <button className={`Polaris-Button btn-paynow ${plan.id == user.plan_id ? "current-cls" : "buynow-cls"}`} id={plan.id} type="button" disabled={ plan.id == user.plan_id ? 'disabled' : null} onClick={onPayClickHandle}>{( plan.id == user.plan_id ) ? 'Current Plan' : 'Buy Now'}</button>

                                    </LegacyCard>
                                </Grid.Cell>
                            })
                        }
                    </Grid>
                </LegacyCard>
            </Layout.Section>
        </Layout>
    </Page>
  )
}
