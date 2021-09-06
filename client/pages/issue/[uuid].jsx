import { getProviders, getSession, getCsrfToken } from "next-auth/client";
import Authenticate from "../../components/layouts/Authenticate";
import Layout from "../../components/layouts/Layout";

function SingleIssue({ session, csrfToken, providers, uuid }) {
  if (!session)
    return <Authenticate csrfToken={csrfToken} providers={providers} />;

  return <Layout title={"Issue - " + uuid}>Issue {uuid}</Layout>;
}

export default SingleIssue;

export async function getServerSideProps(context) {
  const { params } = context;
  const { uuid } = params;

  return {
    props: {
      session: await getSession(context),
      csrfToken: await getCsrfToken(context),
      providers: await getProviders(),
      uuid,
    },
  };
}
